<?php

declare(strict_types=1);

namespace App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\GenerateTokenOutput;
use App\Shared\Infrastructure\Constants\Messages;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class GenerateTokenPresenter implements GenerateTokenOutput
{
    use PresenterTrait;

    public function onSuccess(string $token): void
    {
        $this->response = fn() => response()->json([
            "success" => true,
            "token" => $token,
        ], Response::HTTP_OK);
    }
    public function onMissingInput(string $message): void
    {
        $this->response = fn() => response()->json([
            "success" => false,
            "message" => $message,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function onCredentialFailed(): void
    {
        $this->response = fn() => response()->json([
            "success" => false,
            "message" => "Faild to authinticat , invalid email or password",
        ], Response::HTTP_UNAUTHORIZED);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
