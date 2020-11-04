<?php

declare(strict_types=1);

namespace App\Validator\StatusCode;

use App\Enum\StatusCodeGroup;

final class HttpStatusCodeValidator
{
    private const ERROR_STATUS_CODE_GROUPS = [
        StatusCodeGroup::GROUP_CLIENT_ERROR,
        StatusCodeGroup::GROUP_SERVER_ERROR,
    ];

    public static function isErrorStatusCode(int $statusCode): bool {
        return in_array(round($statusCode, -2), self::ERROR_STATUS_CODE_GROUPS);
    }
}
