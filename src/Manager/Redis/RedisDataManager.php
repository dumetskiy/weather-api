<?php

declare(strict_types=1);

namespace App\Manager\Redis;

use App\ValueObject\AbstractRedisKey;
use Snc\RedisBundle\Client\Phpredis\Client as RedisClient;

class RedisDataManager
{
    private RedisClient $redisClient;

    private int $defaultTTL;

    public function __construct(RedisClient $redisClient, int $defaultTTL)
    {
        $this->redisClient = $redisClient;
        $this->defaultTTL = $defaultTTL;
    }

    public function excludeExistingRedisKeys(array $redisKeys): array
    {
        return array_filter($redisKeys, fn (AbstractRedisKey $redisKey) => !$this->hasKey($redisKey));
    }

    public function hasKey(AbstractRedisKey $redisKey): bool
    {
        return $this->redisClient->get($redisKey->getKey()) !== false;
    }

    public function getValueByKey(AbstractRedisKey $redisKey): string
    {
        return $this->redisClient->get($redisKey->getKey());
    }

    private function setKeyValue(AbstractRedisKey $redisKey, string $value, ?int $ttl = null): void
    {
        $ttl ??= $this->defaultTTL;
        $this->redisClient->set($redisKey->getKey(), $value, $ttl);
    }
}