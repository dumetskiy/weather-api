<?php

declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\RequestConfiguration\RequestConfiguration;
use App\Exception\ApiClient\ApiClientRequestErrorException;
use App\Validator\StatusCode\HttpStatusCodeValidator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;

class AbstractApiClient extends GuzzleClient
{
    /**
     * @throws ApiClientRequestErrorException
     */
    public function callApi(RequestConfiguration $requestConfiguration, $requestOptions = []): string
    {
        try {
            $response = $this->request(
                $requestConfiguration->getMethod(),
                $requestConfiguration->getPath(),
                array_merge_recursive($this->getConfig(), $requestOptions)
            );

            if (HttpStatusCodeValidator::isErrorStatusCode($response->getStatusCode())) {
                throw ApiClientRequestErrorException::create($requestConfiguration->getPath(), $response->getStatusCode());
            }

            return $response->getBody()->getContents();
        } catch (GuzzleException $exception) {
            throw new ApiClientRequestErrorException();
        }
    }

    /**
     * @throws ApiClientRequestErrorException
     */
    public function callApiAsync(RequestConfiguration $requestConfiguration, $requestOptions = []): PromiseInterface
    {
        try {
            return $this->requestAsync(
                $requestConfiguration->getMethod(),
                $requestConfiguration->getPath(),
                array_merge_recursive($this->getConfig(), $requestOptions)
            );
        } catch (GuzzleException $exception) {
            throw new ApiClientRequestErrorException();
        }
    }
}