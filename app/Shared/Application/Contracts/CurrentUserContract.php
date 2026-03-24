<?php
declare (strict_types=1);

namespace App\Shared\Application\Contracts;

use App\Shared\Domain\Entities\User\UserEntity;

interface CurrentUserContract {
    public function id():int;
    public function entity():UserEntity|null;
}
