<?php

declare(strict_types=1);

namespace App\OutputManager\Manager;

interface OutputManagerInterface
{
    public function outputError(string $payload, ?\Exception $exception = null): void;

    public function outputSuccess(string $payload): void;

    public function outputInformation(string $payload): void;

    public function outputWarning(string $payload): void;
}