<?php
declare(strict_types=1);

namespace App\Shared\Application\Traits;

use App\Shared\Application\Enums\Messages;

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

    public function onUnauthorized(): void
    {
        $this->response = fn() => response()->json([
            "success" => false,
            "message" => Messages::UNAUTHORIZED,
        ]);
    }
    public function onFailure(string $error):void{
        $data = [
            "success" => false,
            "message" => Messages::SERVER_ERROR,
        ];
        $this->onDebug($data, $error);
        $this->response = fn() => response()->json($data);
    }
}
