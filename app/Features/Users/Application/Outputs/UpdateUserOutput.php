<?php
declare (strict_types=1);
namespace App\Features\Users\Application\Outputs;

interface UpdateUserOutput{
    public function onSuccess (bool $stauts):void;
    public function onNotFound ():void;
    public function onUnauthorized():void;
    public function onFailure(string $error):void;
}
