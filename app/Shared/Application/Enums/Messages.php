<?php
declare (strict_types= 1);

namespace App\Shared\Application\Enums;

final class Messages {
    //errors
    const SERVER_ERROR = "internal server error";
    const UNAUTHENTICATED = "Unauthenticated :  Please provide a valid API token.";
    const UNAUTHORIZED = "Unauthorized : current user is unable to do this action";
    const VALIDATION_FAILED = "Validation Failed";
    const NOT_FOUND= "record is not found";
}
