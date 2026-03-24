<?php
declare (strict_types=1);
namespace App\Features\Users\Application\Outputs;

use App\Shared\Domain\Entities\User\UserEntity;

interface ShowUserOutput{
    public function onSuccess (UserEntity $userEntity):void;
    public function onNotFound ():void;
    public function onUnauthorized():void;
    public function onFailure (string $error):void;
}