<?php

declare(strict_types=1);

namespace App\Factory\RedisKey;

use App\ValueObject\AbstractRedisKey;

interface RedisKeyFactoryInterface
{
    public function createRedisKey(string $identifier, string $subIdentifier): AbstractRedisKey;
}