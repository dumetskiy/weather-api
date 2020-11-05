<?php

declare(strict_types=1);

namespace App\Exception\DataFormatter;

use App\Exception\WeatherApiException;

class DataFormatterNotFoundException extends WeatherApiException
{
    protected static string $defaultMessage = 'Data formatter not found.';

    public static function create(string $formatterName): self
    {
        return new self(sprintf('No data formatter with name "%s" found.', $formatterName));
    }
}