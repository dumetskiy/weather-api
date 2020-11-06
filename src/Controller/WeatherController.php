<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataProvider\ForecastDataProvider;
use App\DataRenderer\WeatherForecastRenderer;
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
    private ForecastDataProvider $forecastDataProvider;

    private WeatherForecastRenderer $forecastRenderer;

    public function __construct(
        SerializerInterface $serializer,
        ForecastDataProvider $forecastDataProvider,
        WeatherForecastRenderer $forecastRenderer
    ) {
        parent::__construct($serializer);

        $this->forecastDataProvider = $forecastDataProvider;
        $this->forecastRenderer = $forecastRenderer;
    }

    /**
     * @Route("/forecast", methods={Request::METHOD_GET})
     *
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description="Returns weather forecast information for given cities",
     *     @Model(type=City::class)
     * )
     */
    public function listAll()
    {
        $forecastData = $this->forecastDataProvider->getForecast();

        return new Response($this->forecastRenderer->render($forecastData, WeatherForecastRenderer::TEMPLATE_HTML));
    }
}