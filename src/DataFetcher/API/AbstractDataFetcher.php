<?php

declare(strict_types=1);

namespace App\DataFetcher\API;

use App\ApiClient\AbstractApiClient;
use App\ApiClient\RequestConfiguration\AbstractRequestConfigurationProvider;
use App\DataFormatter\Reponse\ResponseDataFormatterFactory;
use App\Exception\ApiClient\ApiClientRequestErrorException;
use App\Serializer\ObjectSerializer;

abstract class AbstractDataFetcher
{
    protected AbstractApiClient $apiClient;

    protected AbstractRequestConfigurationProvider $requestConfigurationProvider;

    protected ObjectSerializer $serializer;

    protected ResponseDataFormatterFactory $responseFormatterFactory;

    public function __construct(
        AbstractApiClient $apiClient,
        AbstractRequestConfigurationProvider $requestConfigurationProvider,
        ObjectSerializer $serializer,
        ResponseDataFormatterFactory $responseFormatterFactory
    ){
        $this->apiClient = $apiClient;
        $this->requestConfigurationProvider = $requestConfigurationProvider;
        $this->serializer = $serializer;
        $this->serializer = $serializer;
        $this->responseFormatterFactory = $responseFormatterFactory;
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

        $responseFormatterName = $requestConfiguration->getResponseFormatterName();

        if ($responseFormatterName) {
            $responseContent = $this->serializer->deserialize($responseContent, 'array', $requestConfiguration->getFormat());
            $this->responseFormatterFactory
                ->getResponseDataFormatter($responseFormatterName)
                ->formatData($responseContent);
        }

        return $this->serializer->deserialize(
            $responseContent,
            $requestConfiguration->getMappedClass(),
            $requestConfiguration->getFormat()
        );
    }
}