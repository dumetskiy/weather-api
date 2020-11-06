<?php

declare(strict_types=1);

namespace App\DataFetcher\API;

use App\ApiClient\AbstractApiClient;
use App\ApiClient\RequestConfiguration\AbstractRequestConfigurationProvider;
use App\ApiClient\RequestConfiguration\RequestConfiguration;
use App\DataFormatter\Reponse\ResponseDataFormatterFactory;
use App\Exception\ApiClient\ApiClientRequestErrorException;
use App\Serializer\ObjectSerializer;
use App\Validator\StatusCode\HttpStatusCodeValidator;
use GuzzleHttp\Promise\Utils as PromiseUtils;
use GuzzleHttp\Psr7\Response;

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
    protected function callApi(string $requestConfigurationName, array $requestOptions = [])
    {
        $requestConfiguration = $this->requestConfigurationProvider->getRequestConfiguration($requestConfigurationName);

        return $this->getResponseData(
            $this->apiClient->callApi($requestConfiguration, $requestOptions) ,
            $requestConfiguration
        );
    }

    protected function bulkAsyncApiCall(string $requestConfigurationName, array $requestOptionsMap = [])
    {
        $requestConfiguration = $this->requestConfigurationProvider->getRequestConfiguration($requestConfigurationName);
        $asyncRequestPromises = [];

        foreach ($requestOptionsMap as $itemKey => $requestOptions) {
            $asyncRequestPromises[$itemKey] = $this->apiClient->callApiAsync($requestConfiguration, $requestOptions);
        }

        $apiResponses = PromiseUtils::unwrap($asyncRequestPromises);
        $apiResponsesData = [];

        /** @var Response $apiResponse */
        foreach ($apiResponses as $itemKey => $apiResponse) {
            if (HttpStatusCodeValidator::isErrorStatusCode($apiResponse->getStatusCode())) {
                throw ApiClientRequestErrorException::create($requestConfiguration->getPath(), $apiResponse->getStatusCode());
            }

            $apiResponsesData[$itemKey] = $this->getResponseData($apiResponse->getBody()->getContents(), $requestConfiguration);
        }

        return $apiResponsesData;
    }

    private function getResponseData(string $responseContent, RequestConfiguration $requestConfiguration)
    {
        if (!$responseContent) {
            return null;
        }

        $responseFormatterHandle = $requestConfiguration->getResponseFormatterHandle();

        if ($responseFormatterHandle) {
            $responseContent = $this->serializer->decode($responseContent, $requestConfiguration->getFormat());
            $this->responseFormatterFactory
                ->getResponseDataFormatter($responseFormatterHandle)
                ->formatData($responseContent);
            $responseContent = $this->serializer->encode($responseContent, $requestConfiguration->getFormat());
        }

        return $this->serializer->deserialize(
            $responseContent,
            $requestConfiguration->getMappedClass(),
            $requestConfiguration->getFormat()
        );
    }
}