<?php

declare(strict_types=1);

namespace App\DataFetcher;

use App\ApiClient\AbstractApiClient;
use App\ApiClient\RequestConfiguration\AbstractRequestConfigurationProvider;
use App\Exception\ApiClient\ApiClientRequestErrorException;
use App\Serializer\ObjectSerializer;

abstract class AbstractDataFetcher
{
    protected AbstractApiClient $apiClient;

    protected AbstractRequestConfigurationProvider $requestConfigurationProvider;

    protected ObjectSerializer $serializer;

    public function __construct(
        AbstractApiClient $apiClient,
        AbstractRequestConfigurationProvider $requestConfigurationProvider,
        ObjectSerializer $serializer
    ){
        $this->apiClient = $apiClient;
        $this->requestConfigurationProvider = $requestConfigurationProvider;
        $this->serializer = $serializer;
    }

    /**
     * @throws ApiClientRequestErrorException
     */
    protected function callApi(string $requestConfigurationName, $requestOptions = [])
    {
        $requestConfiguration = $this->requestConfigurationProvider->getRequestConfiguration($requestConfigurationName);

        $responseContent = $this->apiClient->callApi($requestConfiguration, $requestOptions);

        if (!$responseContent) {
            return null;
        }

        return $this->serializer->deserialize(
            $responseContent,
            $requestConfiguration->getMappedClass(),
            $requestConfiguration->getFormat()
        );
    }
}