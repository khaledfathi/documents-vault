<?php

declare(strict_types=1);

namespace App\Features\Users\Presentation\API\Controllers;

use App\Features\Users\Application\Contracts\DestroyTokenContract;
use App\Features\Users\Application\Contracts\DestroyUserContract;
use App\Features\Users\Application\Contracts\GenerateTokenContract;
use App\Features\Users\Application\Contracts\PaginateUsersContract;
use App\Features\Users\Application\Contracts\ShowUserContract;
use App\Features\Users\Application\Contracts\StoreUserContract;
use App\Features\Users\Application\Contracts\UpdateUserContract;
use App\Features\Users\Presentation\API\Presenters\DestroyTokenPresenter;
use App\Features\Users\Presentation\API\Presenters\DestroyUserPresenter;
use App\Features\Users\Presentation\API\Presenters\GenerateTokenPresenter;
use App\Features\Users\Presentation\API\Presenters\PaginateUsersPresenter;
use App\Features\Users\Presentation\API\Presenters\ShowUserPresenter;
use App\Features\Users\Presentation\API\Presenters\StoreUserPresenter;
use App\Features\Users\Presentation\API\Presenters\UpdateUserPresenter;
use App\Features\Users\Presentation\API\Requests\StoreUserRequest;
use App\Features\Users\Presentation\API\Requests\UpdateUserRequest;
use App\Shared\Domain\Entities\User\PhoneEntity;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Presentation\HTTP\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class UserController extends Controller
{
    public function __construct(
        private readonly GenerateTokenContract $generateTokenUsecase,
        private readonly DestroyTokenContract $destroyTokenUsecase,
        private readonly PaginateUsersContract $paginateUserUsecase,
        private readonly ShowUserContract $showUserUsecase,
        private readonly StoreUserContract $storeUserUsecae,
        private readonly UpdateUserContract $updateUserUsecase,
        private readonly DestroyUserContract $destroyUserUsecase,
    ) {}
    public function login(Request $request)
    {
        $presenter = new GenerateTokenPresenter();
        $this->generateTokenUsecase->execute($request->email ?? '', $request->password ?? '', $presenter);
        return $presenter->handle();
    }
    public function logout()
    {
        $userName = Auth::user()->name;
        $presenter = new DestroyTokenPresenter($userName);
        $this->destroyTokenUsecase->execute($presenter);
        return $presenter->handle();
    }
    public function index(Request $request)
    {
        $presenter = new PaginateUsersPresenter();
        $this->paginateUserUsecase->execute($presenter, (int)($request->per_page ?? 10));
        return $presenter->handle();
    }
    public function show(string $userId)
    {
        $presenter = new ShowUserPresenter();
        $this->showUserUsecase->execute((int) $userId, $presenter);
        return $presenter->handle();
    }
    public function store(StoreUserRequest $request)
    {
        $data = $this->requestToUserEntity($request);
        $presenter =  new StoreUserPresenter();
        $this->storeUserUsecae->execute($data, $presenter);
        return $presenter->handle();
    }
    public function update(UpdateUserRequest $request)
    {
        $data = $this->requestToUserEntity($request);
        $presenter = new UpdateUserPresenter();
        $this->updateUserUsecase->execute($data, $presenter);
        return $presenter->handle();
    }
    public function destroy(string $userId)
    {
        $presenter = new DestroyUserPresenter();
        $this->destroyUserUsecase->execute((int) $userId, $presenter);
        return $presenter->handle();
    }
    private function requestToUserEntity(Request $request): UserEntity
    {
        $userEntity = new UserEntity(
            groupId: $request->group_id ? (int) $request->group_id : null,
            name: $request->name,
            email: $request->email,
            password: $request->password,
        );
        if ($userId = $request->route('user')) {
            $userEntity->id = (int) $userId;
        } elseif ($userId = $request->id) {
            $userEntity->id = (int) $userId;
        }
        $phones = [];
        foreach ($request->phones ?? [] as $phone) {
            $phones[] = new PhoneEntity(
                userId: $userEntity->id,
                phone: $phone
            );
        }
        $userEntity->phones = $phones;
        return $userEntity;
    }
}
