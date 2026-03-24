<?php
declare (strict_types=1);

namespace App\Features\Users\Application\Contracts;

use App\Features\Users\Application\Outputs\StoreUserOutput;
use App\Shared\Domain\Entities\User\UserEntity;

interface StoreUserContract{
    public function execute ( UserEntity $userEntity, StoreUserOutput $presenter):void;
}
