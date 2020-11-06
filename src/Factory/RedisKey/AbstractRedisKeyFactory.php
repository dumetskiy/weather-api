<?php

declare(strict_types=1);

namespace App\Factory\RedisKey;

abstract class AbstractRedisKeyFactory implements RedisKeyFactoryInterface
{
    public function createRedisKeyCombinations(array $identifiers, array $subIdentifiers): array
    {
        $keyCombinations = [];

        foreach ($identifiers as $identifier) {
            foreach ($subIdentifiers as $subIdentifier) {
                $keyCombinations[] = $this->createRedisKey($identifier, $subIdentifier);
            }
        }

        return $keyCombinations;
    }
}