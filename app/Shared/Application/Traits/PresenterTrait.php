<?php
declare(strict_types=1);

namespace App\Shared\Application\Traits;

trait PresenterTrait
{
    /**
     * append the exception message to [$data] 
     * @param array $data
     * @param string $error
     * @return void
     */
    public function onDebug(array &$data , string $error):void
    {
        if(getenv('APP_DEBUG'))  $data['error'] = $error;
    }
}