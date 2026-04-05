<?php
declare(strict_types=1);
namespace App\Features\Users\Application\Outputs;

interface ResetAdminUserOutput {
    public function onSuccess():void;
    public function onFailure(string $error):void;
}
