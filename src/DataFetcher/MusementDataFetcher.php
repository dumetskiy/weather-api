<?php

declare(strict_types=1);

namespace App\DataFetcher;

use App\ApiClient\MusementApiClient;
use App\ApiClient\RequestConfiguration\ConfigurationProvider\MusementRequestConfigurationProvider;
use App\Enum\ApiRequest;
use App\Exception\ApiClient\ApiClientRequestErrorException;
use App\Serializer\ObjectSerializer;

class MusementDataFetcher extends AbstractDataFetcher
{
    public function __construct(
        MusementApiClient $musementApiClient,
        MusementRequestConfigurationProvider $requestConfigurationProvider,
        ObjectSerializer $serializer
    ) {
        parent::__construct($musementApiClient, $requestConfigurationProvider, $serializer);
    }

    /**
     * @throws ApiClientRequestErrorException
     */
    public function getCitiesData(): ?array
    {
        return $this->callApi(ApiRequest::MUSEMENT_CITIES_GET);
    }
}