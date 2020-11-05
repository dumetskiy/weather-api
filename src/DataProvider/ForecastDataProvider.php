<?php

declare(strict_types=1);

namespace App\DataProvider;

use App\DataFetcher\API\WeatherDataFetcher;
use App\DataFormatter\Date\DateDataFormatter;
use App\DataSynchonizer\DataAnalyzer\WeatherDataAnalyzer;
use App\Manager\Entity\CityManager;
use App\OutputManager\Provider\DependencyInjection\OutputManagerProviderAwareInterface;
use App\OutputManager\Provider\DependencyInjection\OutputManagerProviderTrait;

class ForecastDataProvider implements OutputManagerProviderAwareInterface
{
    use OutputManagerProviderTrait;

    private const DEFAULT_FORECAST_DURATION_DAYS = 2;

    private WeatherDataFetcher $weatherDataFetcher;

    private CityManager $cityManager;

    private WeatherDataAnalyzer $weatherDataAnalyzer;

    public function __construct(
        WeatherDataFetcher $weatherDataFetcher,
        CityManager $cityManager,
        WeatherDataAnalyzer $weatherDataAnalyzer
    ) {
        $this->weatherDataFetcher = $weatherDataFetcher;
        $this->cityManager = $cityManager;
        $this->weatherDataAnalyzer = $weatherDataAnalyzer;
    }

    public function getForecast(int $days = self::DEFAULT_FORECAST_DURATION_DAYS): array
    {
        $datesToFetch = DateDataFormatter::getUpcomingDayDatesFormatted($days);
        $citiesMissingForecast = $this->weatherDataAnalyzer->getMissingForecastCities(
            $this->cityManager->fetchAll(),
            $datesToFetch
        );
    }
}