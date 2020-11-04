<?php

declare(strict_types=1);

namespace App\OutputManager\Provider;

use App\OutputManager\Manager\OutputManagerInterface;

class OutputManagerProvider
{
    public function __construct(OutputManagerInterface ...$outputManagers)
    {
        $this->outputManagers = [...$outputManagers];
    }

    /**
     * @var OutputManagerInterface[]
     */
    private array $outputManagers = [];

    public function addOutputManager(OutputManagerInterface $outputManager): void
    {
        $this->outputManagers[] = $outputManager;
    }

    public function outputError(string $payload, ?\Exception $exception = null): void
    {
        foreach ($this->outputManagers as $outputManager) {
            $outputManager->outputError($payload, $exception);
        }
    }

    public function outputSuccess(string $payload): void
    {
        foreach ($this->outputManagers as $outputManager) {
            $outputManager->outputSuccess($payload);
        }
    }

    public function outputInformation(string $payload): void
    {
        foreach ($this->outputManagers as $outputManager) {
            $outputManager->outputInformation($payload);
        }
    }

    public function outputWarning(string $payload): void
    {
        foreach ($this->outputManagers as $outputManager) {
            $outputManager->outputWarning($payload);
        }
    }
}