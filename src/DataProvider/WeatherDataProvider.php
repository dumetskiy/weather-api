<?php

declare(strict_types=1);

namespace App\DataProvider;

use App\DTO\CityWeatherForecastDTO;
use App\DTO\DayWeatherDTO;
use App\DTO\WeatherForecastDTO;
use App\Entity\City;
use App\Factory\RedisKey\ForecastRedisKeyFactory;
use App\Manager\Entity\CityManager;
use App\Manager\Redis\RedisDataManager;
use App\Serializer\ObjectSerializer;
use App\ValueObject\ForecastRecordRedisKey;

class WeatherDataProvider
{
    private ForecastRedisKeyFactory $redisKeyFactory;

    private RedisDataManager $redisDataManager;

    private ObjectSerializer $serializer;

    private CityManager $cityManager;

    public function __construct(
        ForecastRedisKeyFactory $redisKeyFactory,
        RedisDataManager $redisDataManager,
        ObjectSerializer $serializer,
        CityManager $cityManager
    ){
        $this->redisKeyFactory = $redisKeyFactory;
        $this->redisDataManager = $redisDataManager;
        $this->serializer = $serializer;
        $this->cityManager = $cityManager;
    }

    public function getMissingForecastCities(array $cities, array $formattedDates): array
    {
        $forecastRecordsRedisKeys = $this->redisKeyFactory->createRedisKeyCombinations(
            $this->getCitiesCodes($cities),
            $formattedDates
        );

        $missingRedisKeys = $this->redisDataManager->excludeExistingRedisKeys($forecastRecordsRedisKeys);
        $missingForecastCitiesCodes = array_map(fn (ForecastRecordRedisKey $redisKey) => $redisKey->getEntityIdentifier(), $missingRedisKeys);

        return array_filter($cities, fn (City $city) => in_array($city->getCityCode(), $missingForecastCitiesCodes));
    }

    public function getForecastForCitiesAndDates(array $cities, array $dates): WeatherForecastDTO
    {
        $forecastRecordsRedisKeys = $this->redisKeyFactory->createRedisKeyCombinations(
            $this->getCitiesCodes($cities),
            $dates
        );

        return $this->formatForecastData(
            $this->redisDataManager->getKeysValues($forecastRecordsRedisKeys)
        );
    }

    public function formatForecastData(array $redisForecastRecords): WeatherForecastDTO
    {
        $forecastData = [];
        $redisForecastRecords = array_filter($redisForecastRecords);

        foreach ($redisForecastRecords as $redisKeyValue => $redisForecastRecordData) {
            $redisKey = $this->redisKeyFactory->restoreRedisKey($redisKeyValue);

            if (!isset($forecastData['citiesForecast'][$redisKey->getEntityIdentifier()])) {
                $forecastData['citiesForecast'][$redisKey->getEntityIdentifier()] = $this->serializer->denormalize([
                    'city' => $this->cityManager->findByCode($redisKey->getEntityIdentifier()),
                    'daysWeather' => [],
                ], CityWeatherForecastDTO::class);
            }

            /** @var CityWeatherForecastDTO $cityWeather */
            $cityWeather = $forecastData['citiesForecast'][$redisKey->getEntityIdentifier()];

            $cityWeather->addDayWeather(
                $this->serializer->deserialize($redisForecastRecordData, DayWeatherDTO::class, 'json'),
                $redisKey->getSubIdentifier()
            );
        }

        return $this->serializer->denormalize($forecastData, WeatherForecastDTO::class);
    }

    private function getCitiesCodes(array $cities): array
    {
        return array_map(fn (City $city) => $city->getCityCode(), $cities);
    }
}