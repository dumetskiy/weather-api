<?php

declare(strict_types=1);

namespace App\ApiClient;

use App\ApiClient\RequestConfiguration\RequestConfiguration;
use App\Exception\ApiClient\ApiClientRequestErrorException;
use App\Validator\StatusCode\HttpStatusCodeValidator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

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
                $requestOptions
            );

            if (HttpStatusCodeValidator::isErrorStatusCode($response->getStatusCode())) {
                throw ApiClientRequestErrorException::create($requestConfiguration->getPath(), $response->getStatusCode());
            }

            return $response->getBody()->getContents();
        } catch (GuzzleException $exception) {
            throw new ApiClientRequestErrorException();
        }
    }
}