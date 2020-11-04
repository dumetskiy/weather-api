<?php

namespace App\ApiClient\Guzzle\Middleware;

use EightPoints\Bundle\GuzzleBundle\Middleware\SymfonyLogMiddleware;
use GuzzleHttp\MessageFormatter;
use Psr\Log\LoggerInterface;

class ApiClientLogMiddleware extends SymfonyLogMiddleware
{
    public function __construct(LoggerInterface $logger, MessageFormatter $formatter)
    {
        parent::__construct($logger, $formatter);
    }
}