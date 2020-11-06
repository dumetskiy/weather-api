<?php

declare(strict_types=1);

namespace App\ValueObject;

abstract class AbstractRedisKey
{
    protected const ENTITY_KEY = null;

    protected string $entityIdentifier;

    protected ?string $subIdentifier;

    public function __construct(string $identifier, ?string $subIdentifier = null)
    {
        $this->entityIdentifier = $identifier;
        $this->subIdentifier = $subIdentifier;
    }

    public function restoreRedisKey(string $key): self
    {
        $keyElements = explode(':', $key);

        return count($keyElements) === 2 ? new static($keyElements[1]) : new static($keyElements[1], $keyElements[2]);
    }

    public function getEntityIdentifier(): string
    {
        return $this->entityIdentifier;
    }

    public function getSubIdentifier(): ?string
    {
        return $this->subIdentifier;
    }

    public function getKey(): string
    {
        if (!$this->subIdentifier) {
            return sprintf('%s:%s', static::ENTITY_KEY, $this->entityIdentifier);
        }

        return sprintf('%s:%s:%s', static::ENTITY_KEY, $this->entityIdentifier, $this->subIdentifier);
    }
}