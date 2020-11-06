<?php

declare(strict_types=1);

namespace App\DataFormatter\Reponse\Formatter;

use App\DataFormatter\Date\DateDataFormatter;

class WeatherApiForecastResponseDataFormatter extends AbstractResponseDataFormatter
{
    public const FORMATTER_NAME = 'weather_api_forecast';

    public function formatData(array &$responseData): void
    {
        $forecastData = array_map(
            fn (array $forecastDay) => [
                'condition' => $forecastDay['day']['condition']['text'],
                'avgTemperature' => $forecastDay['day']['avgtemp_c'],
                'topTemperature' => $forecastDay['day']['maxtemp_c'],
                'date' => \DateTime::createFromFormat('Y-m-d', $forecastDay['date']),
            ],
            $responseData['forecast']['forecastday']
        );

        $forecastFormattedDates = array_map(
            fn (\DateTime $date) => DateDataFormatter::getFormattedDate($date),
            array_column($forecastData, 'date')
        );

        $responseData = array_combine($forecastFormattedDates, $forecastData);
    }
}
