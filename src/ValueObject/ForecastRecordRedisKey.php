<?php

declare(strict_types=1);

namespace App\ValueObject;

class ForecastRecordRedisKey extends AbstractRedisKey
{
    protected const ENTITY_KEY = 'forecast';
}
