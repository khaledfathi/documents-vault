<?php

declare(strict_types=1);

namespace App\Features\Permissions\Application\Usecases;

use App\Features\Permissions\Application\Contracts\ListPermissionsContract;
use App\Features\Permissions\Application\Outputs\ListPermissionsOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\PermissionRepository;
use Exception;

final class ListPermissionsUsecase implements ListPermissionsContract
{
    public function __construct(
        private readonly CurrentUserContract $currentUser,
        private readonly PermissionGateway $permissionGateway,
        private readonly PermissionRepository $permissionRepository,
    ) {}
    public function execute(ListPermissionsOutput $presenter)
    {
        if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_PERMISSION)) {
            $presenter->onUnauthorized();
            return;
        }
        try {
            $permissionEntities = $this->permissionRepository->index();
            $presenter->onSuccess($permissionEntities);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
