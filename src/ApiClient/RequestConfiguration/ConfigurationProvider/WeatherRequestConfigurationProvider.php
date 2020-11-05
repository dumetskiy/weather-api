<?php

declare(strict_types=1);

namespace App\ApiClient\RequestConfiguration\ConfigurationProvider;

use App\ApiClient\RequestConfiguration\AbstractRequestConfigurationProvider;

class WeatherRequestConfigurationProvider extends AbstractRequestConfigurationProvider
{
    protected const CONFIGURATION_NAME = 'weather';
}