<?php

declare(strict_types=1);

namespace  App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\StoreUserOutput;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Closure;
use Illuminate\Http\Response;

final class StoreUserPresenter implements StoreUserOutput
{
    use PresenterTrait;
    private Closure $response;
    public function onSuccess(UserEntity $userEntity): void
    {
        $this->response = fn() => response()->json($userEntity->toArray(), Response::HTTP_CREATED);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
