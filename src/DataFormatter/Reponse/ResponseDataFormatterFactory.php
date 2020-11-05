<?php

declare(strict_types=1);

namespace App\DataFormatter\Reponse;

use App\DataFormatter\Reponse\Formatter\AbstractResponseDataFormatter;
use App\Exception\DataFormatter\DataFormatterNotFoundException;

class ResponseDataFormatterFactory
{
    private array $responseDataFormatters = [];

    public function addResponseFormatter(AbstractResponseDataFormatter $responseDataFormatter): void
    {
        $this->responseDataFormatters[$responseDataFormatter::FORMATTER_NAME] = $responseDataFormatter;
    }

    public function getResponseDataFormatter(string $formatterName): ?AbstractResponseDataFormatter
    {
        if (!isset($this->responseDataFormatters[$formatterName])) {
            throw DataFormatterNotFoundException::create($formatterName);
        }

        return $this->responseDataFormatters[$formatterName] ?? null;
    }

}