<?php

declare(strict_types=1);

namespace App\DataFormatter\Reponse\Formatter;

class WeatherApiForecastResponseDataFormatter extends AbstractResponseDataFormatter
{
    public const FORMATTER_NAME = 'weather_api_forecast';

    public function formatData(array &$responseData): void
    {
        var_dump($responseData);die;
    }
}
