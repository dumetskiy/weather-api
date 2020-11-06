<?php

declare(strict_types=1);

namespace App\DataFetcher\API;

use App\ApiClient\RequestConfiguration\ConfigurationProvider\WeatherRequestConfigurationProvider;
use App\ApiClient\WeatherApiClient;
use App\DataFormatter\Reponse\ResponseDataFormatterFactory;
use App\Entity\City;
use App\Enum\ApiRequest;
use App\Exception\ApiClient\ApiClientRequestErrorException;
use App\Serializer\ObjectSerializer;

class WeatherDataFetcher extends AbstractDataFetcher
{
    public function __construct(
        WeatherApiClient $weatherApiClient,
        WeatherRequestConfigurationProvider $requestConfigurationProvider,
        ObjectSerializer $serializer,
        ResponseDataFormatterFactory $responseFormatterFactory
    ) {
        parent::__construct($weatherApiClient, $requestConfigurationProvider, $serializer, $responseFormatterFactory);
    }

    /**
     * @throws ApiClientRequestErrorException
     */
    public function getWeatherForCoordinates(float $latitude, float $longitude, int $days): ?array
    {
        return $this->callApi(ApiRequest::WEATHER_FOR_COORDINATES_GET, [
            'query' => [
                'q' => sprintf('%d,%d', $latitude, $longitude)
            ]
        ]);
    }

    /**
     * @param City[] $cities
     */
    public function getWeatherForCities(array $cities, int $days): array
    {
        $requestOptionsMap = [];

        foreach ($cities as $city) {
            $requestOptionsMap[$city->getCityCode()] = [
                'query' => [
                    'q' => sprintf('%f,%f', $city->getLatitude(), $city->getLongitude()),
                    'days' => $days,
                ]
            ];
        }

        return $this->bulkAsyncApiCall(
            ApiRequest::WEATHER_FOR_COORDINATES_GET,
            $requestOptionsMap
        );
    }
}