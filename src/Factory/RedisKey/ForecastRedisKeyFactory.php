<?php

declare(strict_types=1);

namespace App\Factory\RedisKey;

use App\ValueObject\AbstractRedisKey;
use App\ValueObject\ForecastRecordRedisKey;

class ForecastRedisKeyFactory extends AbstractRedisKeyFactory implements RedisKeyFactoryInterface
{
    public function createRedisKey(string $identifier, string $subIdentifier): AbstractRedisKey
    {
        return new ForecastRecordRedisKey($identifier, $subIdentifier);
    }

    public function restoreRedisKey(string $redisKey): AbstractRedisKey
    {
        return ForecastRecordRedisKey::restoreRedisKey($redisKey);
    }
}