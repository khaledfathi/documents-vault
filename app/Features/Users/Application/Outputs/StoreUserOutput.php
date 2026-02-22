<?php
declare (strict_types=1);
namespace App\Features\Users\Application\Outputs;

use App\Shared\Domain\Entities\User\UserEntity;

interface StoreUserOutput {
    public function onSuccess (UserEntity $userEntity); 
    public function onFailure (); 
}