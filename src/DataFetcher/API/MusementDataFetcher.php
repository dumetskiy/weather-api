<?php

declare(strict_types=1);

namespace App\DataFetcher\API;

use App\ApiClient\MusementApiClient;
use App\ApiClient\RequestConfiguration\ConfigurationProvider\MusementRequestConfigurationProvider;
use App\DataFormatter\Reponse\ResponseDataFormatterFactory;
use App\Enum\ApiRequest;
use App\Exception\ApiClient\ApiClientRequestErrorException;
use App\Serializer\ObjectSerializer;

class MusementDataFetcher extends AbstractDataFetcher
{
    public function __construct(
        MusementApiClient $musementApiClient,
        MusementRequestConfigurationProvider $requestConfigurationProvider,
        ObjectSerializer $serializer,
        ResponseDataFormatterFactory $responseFormatterFactory
    ) {
        parent::__construct($musementApiClient, $requestConfigurationProvider, $serializer, $responseFormatterFactory);
    }

    /**
     * @throws ApiClientRequestErrorException
     */
    public function getCitiesData(): ?array
    {
        return $this->callApi(ApiRequest::MUSEMENT_CITIES_GET);
    }
}