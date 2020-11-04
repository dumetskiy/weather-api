<?php

declare(strict_types=1);

namespace App\ApiClient\RequestConfiguration\Formatter;

use App\ApiClient\RequestConfiguration\RequestConfiguration;
use App\Serializer\ObjectSerializer;

class RequestConfigurationFormatter
{
    private ObjectSerializer $serializer;

    public function __construct(ObjectSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function formatArrayToRequestConfiguration(array $requestConfigurationData): RequestConfiguration
    {
        return $this->serializer->denormalize($requestConfigurationData, RequestConfiguration::class);
    }

    public function formatRequestConfigurationToArray(RequestConfiguration $requestConfiguration): array
    {
        return $this->serializer->normalize($requestConfiguration);
    }
}
