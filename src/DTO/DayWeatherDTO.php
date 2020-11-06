<?php

declare(strict_types=1);

namespace App\DTO;

class DayWeatherDTO
{
    private string $condition;

    private float $avgTemperature;

    private float $topTemperature;

    public function getCondition(): string
    {
        return $this->condition;
    }

    public function setCondition(string $condition): void
    {
        $this->condition = $condition;
    }

    public function getAvgTemperature(): float
    {
        return $this->avgTemperature;
    }

    public function setAvgTemperature(float $avgTemperature): void
    {
        $this->avgTemperature = $avgTemperature;
    }

    public function getTopTemperature(): float
    {
        return $this->topTemperature;
    }

    public function setTopTemperature(float $topTemperature): void
    {
        $this->topTemperature = $topTemperature;
    }
}
