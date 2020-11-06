<?php

declare(strict_types=1);

namespace App\DataProvider;

use App\DataFetcher\API\WeatherDataFetcher;
use App\DataFormatter\Date\DateDataFormatter;
use App\DTO\WeatherForecastDTO;
use App\Entity\City;
use App\Manager\Entity\CityManager;
use App\Manager\Redis\RedisDataManager;
use App\OutputManager\Provider\DependencyInjection\OutputManagerProviderAwareInterface;
use App\OutputManager\Provider\DependencyInjection\OutputManagerProviderTrait;
use App\Serializer\ObjectSerializer;
use App\ValueObject\ForecastRecordRedisKey;

class ForecastDataProvider implements OutputManagerProviderAwareInterface
{
    use OutputManagerProviderTrait;

    private const DEFAULT_FORECAST_DURATION_DAYS = 3;

    private WeatherDataFetcher $weatherDataFetcher;

    private CityManager $cityManager;

    private WeatherDataProvider $weatherDataProvider;

    private RedisDataManager $redisDataManager;

    private ObjectSerializer $objectSerializer;

    public function __construct(
        WeatherDataFetcher $weatherDataFetcher,
        CityManager $cityManager,
        WeatherDataProvider $weatherDataProvider,
        RedisDataManager $redisDataManager,
        ObjectSerializer $objectSerializer
    ) {
        $this->weatherDataFetcher = $weatherDataFetcher;
        $this->cityManager = $cityManager;
        $this->weatherDataProvider = $weatherDataProvider;
        $this->redisDataManager = $redisDataManager;
        $this->objectSerializer = $objectSerializer;
    }

    public function getForecast(?array $cities = null, int $days = self::DEFAULT_FORECAST_DURATION_DAYS): WeatherForecastDTO
    {
        $cities ??= $this->cityManager->fetchAll();
        $datesToFetch = DateDataFormatter::getUpcomingDayDatesFormatted($days);
        $this->outputInformation(sprintf('Fetching forecast for %s', implode(', ', $datesToFetch)));
        $citiesMissingForecast = $this->weatherDataProvider->getMissingForecastCities($cities, $datesToFetch);

        if (count($citiesMissingForecast)) {
            $this->fetchForecastForCitiesAndDays($citiesMissingForecast, $days);
        }

        return $this->weatherDataProvider->getForecastForCitiesAndDates($cities, $datesToFetch);
    }

    private function fetchForecastForCitiesAndDays(array $cities, int $days): void
    {
        $this->outputInformation(sprintf(
            'Missing forecast for cities: %s. Fetching...',
            implode(', ', array_map(fn (City $city) => $city->getName(), $cities))
        ));
        $citiesForecast = $this->weatherDataFetcher->getWeatherForCities($cities, $days);
        $this->outputSuccess('Forecast successfully fetched.');
        $this->redisDataManager->setKeysValues(
            $this->transformForecastDataIntoRedisKeyValueMap($citiesForecast)
        );
    }

    private function transformForecastDataIntoRedisKeyValueMap(array $forecastData): array
    {
        $redisEntries = [];

        foreach ($forecastData as $cityCode => $cityForecastData) {
            foreach ($cityForecastData as $date => $cityDayForecastData) {
                $redisEntries[(new ForecastRecordRedisKey($cityCode, $date))->getKey()]
                    = $this->objectSerializer->serialize($cityDayForecastData, 'json');
            }
        }

        return $redisEntries;
    }
}