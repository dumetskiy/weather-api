<?php

declare(strict_types=1);

namespace App\Exception\Console;

use App\Exception\WeatherApiException;

class ConsoleRuntimeException extends WeatherApiException
{
    protected static string $defaultMessage = 'Console runtime exception occurred';
}