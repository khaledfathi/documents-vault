<?php

declare(strict_types=1);

namespace App\Features\Groups\Presentation\API\Controllers;

use App\Features\Groups\Application\Contracts\DestroyGroupContract;
use App\Features\Groups\Application\Contracts\ShowGroupContract;
use App\Features\Groups\Application\Contracts\StoreGroupContract;
use App\Features\Groups\Presentation\API\Presenters\DestroyGroupPresenter;
use App\Features\Groups\Presentation\API\Presenters\ShowGroupPresenter;
use App\Features\Groups\Presentation\API\Presenters\StoreGroupPresenter;
use App\Shared\Domain\Entities\User\GroupEntity;
use App\Shared\Domain\Entities\User\PermissionEntity;
use App\Shared\Presentation\HTTP\Controller;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct(
        private readonly StoreGroupContract $storeGroupUsecase,
        private readonly ShowGroupContract $showGroupUsecase,
        private readonly DestroyGroupContract $destroyGroupUsecase,
    ) {}
    public function index(Request $request)
    {
        return __CLASS__ . ":" . __FUNCTION__;
    }
    public function show(string $groupId)
    {
        $presenter = new ShowGroupPresenter();
        $this->showGroupUsecase->execute((int) $groupId, $presenter);
        return $presenter->handle();
    }
    public function store(Request $request)
    {
        $presenter = new StoreGroupPresenter();
        $this->storeGroupUsecase->execute($this->requestToGroupEntity($request), $presenter);
        return $presenter->handle();
    }
    public function update(Request $request, string $groupId)
    {
        return __CLASS__ . ":" . __FUNCTION__;
    }
    public function destroy( string $groupId)
    {
        $presenter = new DestroyGroupPresenter();
        $this->destroyGroupUsecase->execute((int) $groupId, $presenter);
        return $presenter->handle();
    }
    private function requestToGroupEntity(Request $request): GroupEntity
    {
        $permissions = [];
        foreach (array_unique($request->permission_ids ?? []) as $permission_id) {
            $permissions[] = new PermissionEntity(
                id: $permission_id,
            );
        }
        return new GroupEntity(
            id: $request->id,
            name: $request->name,
            permissions: $permissions,
        );
    }
}
