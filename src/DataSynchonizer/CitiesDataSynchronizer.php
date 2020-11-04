<?php

declare(strict_types=1);

namespace App\DataSynchonizer;

use App\DataFetcher\MusementDataFetcher;
use App\DataSynchonizer\DataAnalyzer\CitiesDataAnalyzer;
use App\Entity\City;
use App\Exception\ApiClient\ApiClientRequestErrorException;
use App\Exception\Console\ConsoleRuntimeException;
use App\OutputManager\Provider\DependencyInjection\OutputManagerProviderAwareInterface;
use App\OutputManager\Provider\DependencyInjection\OutputManagerProviderTrait;
use Doctrine\ORM\EntityManagerInterface;

class CitiesDataSynchronizer implements OutputManagerProviderAwareInterface
{
    use OutputManagerProviderTrait;

    private MusementDataFetcher $musementDataFetcher;

    private CitiesDataAnalyzer $citiesDataAnalyzer;

    private EntityManagerInterface $entityManager;

    public function __construct(
        MusementDataFetcher $musementDataFetcher,
        CitiesDataAnalyzer $citiesDataAnalyzer,
        EntityManagerInterface $entityManager
    ) {
        $this->musementDataFetcher = $musementDataFetcher;
        $this->citiesDataAnalyzer = $citiesDataAnalyzer;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ConsoleRuntimeException
     */
    public function synchronizeCities(bool $applyChanges): void
    {
        try {
            if (!$applyChanges) {
                $this->outputWarning('Dry run, no changes will be applied');
            }

            $this->outputInformation('Starting cities synchronisation...');
            $this->outputInformation('Fetching Musement cities from the API...');

            $musementApiCities = $this->musementDataFetcher->getCitiesData();

            if (!$musementApiCities) {
                $this->outputError('No cities data found. Aborting.');

                return;
            }

            $this->outputSuccess(sprintf('Fetched %d cities.', count($musementApiCities)));

            $this->outputInformation('Fetching currently saved cities...');
            $existingCities = $this->entityManager->getRepository(City::class)->findAll();
            $this->outputSuccess(sprintf('Fetched %d cities.', count($existingCities)));

            $this->outputInformation('Analyzing fetched cities...');

            $newCities = $this->citiesDataAnalyzer->getNewlyCreatedCities($existingCities, $musementApiCities);
            $removedCities = $this->citiesDataAnalyzer->getRecentlyRemovedCities($existingCities, $musementApiCities);
            $unchangedCities = $this->citiesDataAnalyzer->getUnchangedCities($existingCities, $musementApiCities);

            $this->outputSuccess(sprintf(
                'Result: %d new, %d removed, %d unchanged cities found',
                count($newCities),
                count($removedCities),
                count($unchangedCities),
            ));

            if (!$applyChanges) {
                return;
            }

            $this->outputInformation('Applying DB changes...');

            foreach ($newCities as $newCity) {
                $this->entityManager->persist($newCity);
            }

            foreach ($removedCities as $removedCity) {
                $this->entityManager->remove($removedCity);
            }

            $this->entityManager->flush();
            $this->outputSuccess('DB changes successfully applied.');
        } catch (ApiClientRequestErrorException $exception) {
            $this->outputError($exception->getMessage());

            throw new ConsoleRuntimeException();
        }
    }
}
