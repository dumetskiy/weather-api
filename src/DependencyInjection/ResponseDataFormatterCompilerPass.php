<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\DataFormatter\Reponse\ResponseDataFormatterFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ResponseDataFormatterCompilerPass
{
    private const RESPONSE_FORMATTER_SERVICE_TAG = 'app.response_data_formatter';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ResponseDataFormatterFactory::class)) {
            return;
        }

        $responseDataFormatterFactoryDefinition = $container->findDefinition(ResponseDataFormatterFactory::class);
        $responseFormatters = $container->findTaggedServiceIds(self::RESPONSE_FORMATTER_SERVICE_TAG);

        foreach ($responseFormatters as $serviceId => $responseFormatter) {
            $responseDataFormatterFactoryDefinition->addMethodCall('addResponseFormatter', [new Reference($serviceId)]);
        }
    }
}