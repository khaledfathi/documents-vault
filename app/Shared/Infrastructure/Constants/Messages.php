<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Constants;

final class Messages
{
    //errors
    public const SERVER_ERROR = "internal server error";
    public const UNAUTHENTICATED = "Unauthenticated :  Please provide a valid API token.";
    public const UNAUTHORIZED = "Unauthorized : current user is unable to do this action";
    public const VALIDATION_FAILED = "Validation Failed";
    public const NOT_FOUND = "record is not found";
}
