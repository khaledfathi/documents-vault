<?php

declare(strict_types=1);

namespace App\Features\Users\Presentation\API\Controllers;

use App\Features\Users\Application\Contracts\DestroyTokenContract;
use App\Features\Users\Application\Contracts\GenerateTokenContract;
use App\Features\Users\Application\Contracts\ShowUserContract;
use App\Features\Users\Application\Contracts\StoreUserContract;
use App\Features\Users\Presentation\API\Presenters\DestroyTokenPresenter;
use App\Features\Users\Presentation\API\Presenters\GenerateTokenPresenter;
use App\Features\Users\Presentation\API\Presenters\ShowUserPresenter;
use App\Features\Users\Presentation\API\Presenters\StoreUserPresenter;
use App\Http\Controllers\Controller;
use App\Shared\Domain\Entities\User\PhoneEntity;
use App\Shared\Domain\Entities\User\UserEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private readonly ShowUserContract  $showUserUsecase,
        private readonly StoreUserContract $storeUserUsecae,
        private readonly GenerateTokenContract $generateTokenUsecase,
        private readonly DestroyTokenContract $destroyTokenUsecase,
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
        return response()->json(['target' => __CLASS__ . ":" . __FUNCTION__]);
    }
    public function show(string $userId)
    {
        $presenter = new ShowUserPresenter();
        $this->showUserUsecase->execute((int) $userId, $presenter);
        return $presenter->handle();
    }
    public function create()
    {
        return response()->json(['target' => __CLASS__ . ":" . __FUNCTION__]);
    }
    public function store(Request $request)
    {
        $data = $this->requestToUserEntity($request);
        $presenter =  new StoreUserPresenter();
        $this->storeUserUsecae->execute($data, $presenter);
        return $presenter->handle();
    }
    public function edit()
    {
        return response()->json(['target' => __CLASS__ . ":" . __FUNCTION__]);
    }
    public function update()
    {
        return response()->json(['target' => __CLASS__ . ":" . __FUNCTION__]);
    }
    public function delete()
    {
        return response()->json(['target' => __CLASS__ . ":" . __FUNCTION__]);
    }
    private function requestToUserEntity(Request $request): UserEntity
    {
        $phones = [];
        foreach($request->phones ?? [] as $phone){
            $phones[] = new PhoneEntity(
                phone: $phone
            );
        }
        return new UserEntity(
            groupId: $request->group_id ? (int)$request->group_id : null,
            name: $request->name,
            email: $request->email,
            password: $request->password,
            phones: $phones,
        );
    }
}
