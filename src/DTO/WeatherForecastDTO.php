<?php

declare(strict_types=1);

namespace App\DTO;

class WeatherForecastDTO
{
    /**
     * @var CityWeatherForecastDTO[]
     */
    private array $citiesForecast;

    public function getCitiesForecast(): array
    {
        return $this->citiesForecast;
    }

    public function setCitiesForecast(array $citiesForecast): void
    {
        $this->citiesForecast = $citiesForecast;
    }
}