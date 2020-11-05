<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Manager\Entity\CityManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/cities", name="cities_")
 */
class CityController extends BaseController
{
    private CityManager $cityManager;

    public function __construct(SerializerInterface $serializer, CityManager $cityManager)
    {
        parent::__construct($serializer);

        $this->cityManager = $cityManager;
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
        return $this->getJsonResponse($this->cityManager->fetchAll());
    }

    /**
     * @Route("/id/{musementCityId}", methods={Request::METHOD_GET})
     *
     * @SWG\Parameter(
     *     name="musementCityId",
     *     in="path",
     *     type="integer",
     *     description="Musement city ID"
     * )
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description="Returns city details details by ID.",
     *     @Model(type=City::class)
     * )
     */
    public function detailsById(City $city)
    {
        return $this->getJsonResponse($city);
    }

    /**
     * @Route("/code/{cityCode}", methods={Request::METHOD_GET})
     *
     * @SWG\Parameter(
     *     name="cityCode",
     *     in="path",
     *     type="string",
     *     description="Musement city code"
     * )
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description="Returns city details details by city code.",
     *     @Model(type=City::class)
     * )
     */
    public function detailsByCode(City $city)
    {
        return $this->getJsonResponse($city);
    }
}