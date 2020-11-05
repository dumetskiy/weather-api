<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataFetcher\API\WeatherDataFetcher;
use App\DataProvider\ForecastDataProvider;
use App\Entity\City;
use App\Manager\Entity\CityManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/weather", name="weather_")
 */
class WeatherController extends BaseController
{
    private CityManager $cityManager;

    private ForecastDataProvider $forecastDataProvider;

    public function __construct(SerializerInterface $serializer, CityManager $cityManager, ForecastDataProvider $forecastDataProvider)
    {
        parent::__construct($serializer);

        $this->cityManager = $cityManager;
        $this->forecastDataProvider = $forecastDataProvider;
    }

    /**
     * @Route("/list", methods={Request::METHOD_GET})
     *
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description="Returns cities currently stored in the database.",
     *     @Model(type=City::class)
     * )
     */
    public function listAll()
    {
        $this->forecastDataProvider->getForecast(2);

        return $this->getJsonResponse([]);
    }


}