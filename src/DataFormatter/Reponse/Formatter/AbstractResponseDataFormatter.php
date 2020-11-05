<?php

declare(strict_types=1);

namespace App\DataFormatter\Reponse\Formatter;

abstract class AbstractResponseDataFormatter
{
    public const FORMATTER_NAME = null;

    public abstract function formatData(array &$responseData): void;
}