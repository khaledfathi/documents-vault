<?php

declare(strict_types=1);

namespace  App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\StoreUserOutput;
use App\Shared\Application\Enums\Messages;
use App\Shared\Application\Traits\PresenterTrait;
use App\Shared\Domain\Entities\User\UserEntity;
use Closure;

final class StoreUserPresenter implements StoreUserOutput
{
    use PresenterTrait;
    private Closure $response;
    public function onSuccess(UserEntity $userEntity): void
    {
        $this->response = fn() => response()->json($userEntity->toArray());
    }
    public function onUnauthorized(): void
    {
        $this->response = fn() => response()->json([
            "success" => false,
            "message" => Messages::UNAUTHORIZED,
        ]);
    }
    public function onFailure(string $error): void
    {
        $data = [
            "success" => false,
            "message" => Messages::SERVER_ERROR,
        ];
        $this->onDebug($data, $error);
        $this->response = fn() => response()->json($data);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
