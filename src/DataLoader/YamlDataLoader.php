<?php

declare(strict_types=1);

namespace App\DataLoader;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class YamlDataLoader
{
    private const CATEGORY_API_CLIENT = 'api_client';

    private string $projectRootDirectory;

    public function __construct(KernelInterface $kernel)
    {
        $this->projectRootDirectory = $kernel->getProjectDir();
    }

    public function getApiClientRequestConfigurationData(string $configurationName): ?array
    {
        return $this->loadDataFromFile($this->buildYamlFilePath(self::CATEGORY_API_CLIENT, $configurationName));
    }

    private function loadDataFromFile(string $filePath): ?array
    {
        return Yaml::parseFile($filePath);
    }

    private function buildYamlFilePath(string $category, string $fileName): string
    {
        return sprintf('%s/config/%s/%s.yaml', $this->projectRootDirectory, $category, $fileName);
    }
}
