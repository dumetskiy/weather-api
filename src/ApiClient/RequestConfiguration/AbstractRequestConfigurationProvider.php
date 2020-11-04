<?php

declare(strict_types=1);

namespace App\ApiClient\RequestConfiguration;

use App\ApiClient\RequestConfiguration\Formatter\RequestConfigurationFormatter;
use App\DataLoader\YamlDataLoader;

abstract class AbstractRequestConfigurationProvider
{
    protected const CONFIGURATION_NAME = '';

    private array $requestConfigurations;

    private RequestConfigurationFormatter $requestConfigurationFormatter;

    private YamlDataLoader $yamlDataLoader;

    public function __construct(RequestConfigurationFormatter $requestConfigurationFormatter, YamlDataLoader $yamlDataLoader)
    {
        $this->requestConfigurationFormatter = $requestConfigurationFormatter;
        $this->yamlDataLoader = $yamlDataLoader;

        $this->initConfigurations();
    }

    public function getRequestConfiguration(string $requestHandle): ?RequestConfiguration
    {
        return $this->requestConfigurations[$requestHandle] ?? null;
    }

    private function initConfigurations(): void
    {
        $configurationData = $this->yamlDataLoader->getApiClientRequestConfigurationData(static::CONFIGURATION_NAME);

        if (!is_array($configurationData) || !count($configurationData)) {
            return;
        }

        foreach ($configurationData as $requestName => $requestConfigurationData) {
            $this->requestConfigurations[$requestName]
                = $this->requestConfigurationFormatter->formatArrayToRequestConfiguration($requestConfigurationData);
        }
    }
}
