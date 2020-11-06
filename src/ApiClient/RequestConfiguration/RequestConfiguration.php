<?php

declare(strict_types=1);

namespace App\ApiClient\RequestConfiguration;

use Symfony\Component\Serializer\Annotation\SerializedName;

class RequestConfiguration
{
    private const FORMAT_JSON = 'json';

    private string $method;

    private string $path;

    private string $mappedClass;

    private ?string $responseFormatterHandle = null;

    private string $format = self::FORMAT_JSON;

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getMappedClass(): string
    {
        return $this->mappedClass;
    }

    public function setMappedClass(string $mappedClass): void
    {
        $this->mappedClass = $mappedClass;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function getResponseFormatterHandle(): ?string
    {
        return $this->responseFormatterHandle;
    }

    public function setResponseFormatterHandle(?string $responseFormatterHandle): void
    {
        $this->responseFormatterHandle = $responseFormatterHandle;
    }
}
