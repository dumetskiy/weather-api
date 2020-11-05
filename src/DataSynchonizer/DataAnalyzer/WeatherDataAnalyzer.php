<?php

declare(strict_types=1);

namespace App\DataSynchonizer\DataAnalyzer;

use App\Entity\City;
use App\Factory\RedisKey\ForecastRedisKeyFactory;
use App\Manager\Redis\RedisDataManager;
use App\ValueObject\ForecastRecordRedisKey;

class WeatherDataAnalyzer
{
    private ForecastRedisKeyFactory $redisKeyFactory;

    private RedisDataManager $redisDataManager;

    public function __construct(ForecastRedisKeyFactory $redisKeyFactory, RedisDataManager $redisDataManager)
    {
        $this->redisKeyFactory = $redisKeyFactory;
        $this->redisDataManager = $redisDataManager;
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

    private function getCitiesCodes(array $cities): array
    {
        return array_map(fn (City $city) => $city->getCityCode(), $cities);
    }
}