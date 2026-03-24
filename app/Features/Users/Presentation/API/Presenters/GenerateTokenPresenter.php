<?php

declare(strict_types=1);

namespace App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\GenerateTokenOutput;
use App\Shared\Application\Enums\Messages;
use App\Shared\Application\Traits\PresenterTrait;
use Closure;


final class GenerateTokenPresenter implements GenerateTokenOutput
{
    use PresenterTrait;
    private Closure $response;
    public function onSuccess(string $token): void
    {
        $this->response = fn() => response()->json([
            "success" => true,
            "token" => $token
        ], 200);
    }
    public function onMissingInput(string $message): void
    {
        $this->response = fn() => response()->json([
            "success" => false,
            "message" => $message
        ], 422);
    }
    public function onCredentialFailed(): void
    {
        $this->response = fn() => response()->json([
            "success" => false,
            "message" => "Faild to authinticat ,
            invalid email or password"
        ], 401);
    }
    public function onFailure(string $error): void
    {
        $data = [
            "success" => false,
            "message" => Messages::SERVER_ERROR
        ];
        $this->onDebug($data, $error);
        $this->response = fn() => response()->json($data, 500);
        //Log the error
    }
    public function handle()
    {
        return ($this->response)();
    }
}
