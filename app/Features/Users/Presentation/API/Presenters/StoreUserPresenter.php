<?php

declare(strict_types=1);

namespace App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\StoreUserOutput;
use App\Shared\Domain\Entities\User\UserEntity;

final class StoreUserPresenter implements StoreUserOutput
{
    public function onSuccess(UserEntity $userEntity) {

    }
    public function onFailure() {

    }
    public function handle(){

    }
}
