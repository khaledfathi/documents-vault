<?php

declare(strict_types=1);

namespace App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\ShowUserOutput;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class ShowUserPresenter implements ShowUserOutput
{
    use PresenterTrait;

    public function onSuccess(UserEntity $userEntity): void
    {
        $this->response = fn() => response()->json([
            "success" => true,
            "message" => "",
            'data' => $userEntity->toArray(),
        ], Response::HTTP_OK);
    }
    public function onNotFound(): void
    {
        $this->response = fn() => $this->notFoundResponse("user is not found");
    }
    public function handle()
    {
        return ($this->response)();
    }
}
