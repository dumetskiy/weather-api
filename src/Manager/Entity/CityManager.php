<?php

declare(strict_types=1);

namespace App\Manager\Entity;

use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;

class CityManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function fetchAll(): array
    {
        return $this->entityManager->getRepository(City::class)->findAll();
    }

    public function findByCode(string $code): City
    {
        return $this->entityManager->getRepository(City::class)->findOneBy([
            'cityCode' => $code,
        ]);
    }

    public function removeCities(array $cities): void
    {
        foreach ($cities as $city) {
            $this->entityManager->remove($city);
        }
    }

    public function createCities(array $cities): void
    {
        foreach ($cities as $city) {
            $this->entityManager->persist($city);
        }
    }

    public function applyDBChanges(): void
    {
        $this->entityManager->flush();
    }
}