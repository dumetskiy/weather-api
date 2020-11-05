<?php

declare(strict_types=1);

namespace App\Exception\ApiClient;

use App\Exception\WeatherApiException;

class ApiClientRequestErrorException extends WeatherApiException
{
    protected static string $defaultMessage = 'An error occurred when calling an API.';

    public static function create(string $url, int $responseCode): self
    {
        return new self(sprintf('An HTTP call to %s resulted in an error response [%s]', $url, $responseCode));
    }
}