<?php

declare(strict_types=1);

namespace App\OutputManager\Provider\DependencyInjection;

use App\OutputManager\Provider\OutputManagerProvider;

interface OutputManagerProviderAwareInterface
{
    public function setOutputManagerProvider(OutputManagerProvider $outputManagerProvider): void;
}