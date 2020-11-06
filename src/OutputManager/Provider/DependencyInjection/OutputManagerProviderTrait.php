<?php

declare(strict_types=1);

namespace App\OutputManager\Provider\DependencyInjection;

use App\OutputManager\Provider\OutputManagerProvider;

trait OutputManagerProviderTrait
{
    private ?OutputManagerProvider $outputManagerProvider = null;

    public function setOutputManagerProvider(OutputManagerProvider $outputManagerProvider): void
    {
        $this->outputManagerProvider = $outputManagerProvider;
    }

    protected function outputWarning(string $payload): void
    {
        if (!$this->hasOutputManagerProvider()) {
            return;
        }

        $this->outputManagerProvider->outputWarning($payload);
    }

    protected function outputSuccess(string $payload): void
    {
        if (!$this->hasOutputManagerProvider()) {
            return;
        }

        $this->outputManagerProvider->outputSuccess($payload);
    }

    protected function outputInformation(string $payload): void
    {
        if (!$this->hasOutputManagerProvider()) {
            return;
        }

        $this->outputManagerProvider->outputInformation($payload);
    }

    protected function outputError(string $payload): void
    {
        if (!$this->hasOutputManagerProvider()) {
            return;
        }

        $this->outputManagerProvider->outputError($payload);
    }

    private function hasOutputManagerProvider(): bool
    {
        return $this->outputManagerProvider && $this->outputManagerProvider instanceof OutputManagerProvider;
    }
}