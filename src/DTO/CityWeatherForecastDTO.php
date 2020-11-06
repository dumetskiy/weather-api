<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\City;

class CityWeatherForecastDTO
{
    private City $city;

    private array $daysWeather;

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): void
    {
        $this->city = $city;
    }

    public function getDaysWeather(): array
    {
        return $this->daysWeather;
    }

    public function getDayWeather(string $date): DayWeatherDTO
    {
        return $this->daysWeather[$date];
    }

    public function setDaysWeather(array $daysWeather): void
    {
        $this->daysWeather = $daysWeather;
    }

    public function addDayWeather(DayWeatherDTO $dayWeatherDTO, string $date): void
    {
        $this->daysWeather[$date] = $dayWeatherDTO;
    }
}