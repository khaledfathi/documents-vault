<?php
declare (strict_types=1);

namespace App\Features\Users\Application\Contracts;

use App\Features\Users\Application\Outputs\UpdateUserOutput;
use App\Shared\Domain\Entities\User\UserEntity;

interface UpdateUserContract{
    public function execute (UserEntity $userEntity, UpdateUserOutput $presenter);
}
