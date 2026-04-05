<?php 
declare(strict_types= 1);
namespace App\Features\Users\Application\Outputs;

use Exception; 

interface GenerateTokenOutput{
    public function onSuccess (string $token):void ; 
    public function onMissingInput(string $message):void ; 
    public function onCredentialFailed ():void ; 
    public function onFailure(string $error):void ; 
}