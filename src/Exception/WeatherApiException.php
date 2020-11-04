<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class WeatherApiException extends \Exception
{
    protected static string $defaultMessage = 'Weather API exception occurred';

    public function __construct(?string $message = null, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?? self::$defaultMessage, $code, $previous);
    }
}