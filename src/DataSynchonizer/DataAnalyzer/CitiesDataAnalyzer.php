<?php

declare(strict_types=1);

namespace App\DataSynchonizer\DataAnalyzer;

use App\Entity\City;

class CitiesDataAnalyzer
{
    /**
     * @param City[] $oldCities
     * @param City[] $newCities
     *
     * @return City[]
     */
    public function getNewlyCreatedCities(array $oldCities, array $newCities): array
    {
        // Returns an array of cities which are present only in $newCities
        return array_filter($newCities, fn (City $newCity) =>
            !count(array_filter($oldCities, fn (City $oldCity) =>
                $oldCity->getMusementCityId() === $newCity->getMusementCityId()
            ))
        );
    }

    /**
     * @param City[] $oldCities
     * @param City[] $newCities
     *
     * @return City[]
     */
    public function getRecentlyRemovedCities(array $oldCities, array $newCities): array
    {
        // Returns an array of cities which are present only in $oldCities
        return array_filter($oldCities, fn (City $oldCity) =>
            !count(array_filter($newCities, fn (City $newCity) =>
                $oldCity->getMusementCityId() === $newCity->getMusementCityId()
            ))
        );
    }

    /**
     * @param City[] $oldCities
     * @param City[] $newCities
     *
     * @return City[]
     */
    public function getUnchangedCities(array $oldCities, array $newCities): array
    {
        // Returns an array of cities which are present in both arrays
        return array_filter($oldCities, fn (City $oldCity) =>
            count(array_filter($newCities, fn (City $newCity) =>
                $oldCity->getMusementCityId() === $newCity->getMusementCityId()
            ))
        );
    }
}