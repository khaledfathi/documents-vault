<?php
declare (strict_types=1);
namespace App\Features\Users\Application\Outputs;

interface UpdateUserOutput{
    public function onSuccess (bool $stauts);
    public function onNotFound ();
    public function onUnauthorized():void;
    public function onFailure(string $error):void;
}
