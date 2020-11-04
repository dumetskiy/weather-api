<?php

declare(strict_types=1);

namespace App\Enum;

final class StatusCodeGroup
{
    public const GROUP_IN_PROGRESS = 100;
    public const GROUP_SUCCESSFUL = 200;
    public const GROUP_REDIRECT = 300;
    public const GROUP_CLIENT_ERROR = 400;
    public const GROUP_SERVER_ERROR = 500;
}