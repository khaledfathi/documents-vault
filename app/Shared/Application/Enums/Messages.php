<?php
declare (strict_types= 1);

namespace App\Shared\Application\Enums;

final class Messages {
    //errors
    const SERVER_ERROR = "internal server error";
    const UNAUTHORIZED = "current user is Unauthorized to do this action";
    const NOT_FOUND= "record is not found";
}
