<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Usecases;

use App\Features\Groups\Application\Contracts\DestroyGroupContract;
use App\Features\Groups\Application\Outputs\DestroyGroupOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\User\GroupEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use Exception;

final class DestroyGroupUsecase implements DestroyGroupContract
{
    public function __construct(
        private readonly GroupRepository $groupRepository,
        private readonly CurrentUserContract $currentUser,
        private readonly PermissionGateway $permissionGateway,
    ) {}
    public function execute(int $groupId, DestroyGroupOutput $presenter): void
    {
        if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::DELETE_GROUP)) {
            $presenter->onUnauthorized();
            return;
        }
        if (GroupEntity::isDefaultGroup($groupId)) {
            $presenter->onDefaultGroups();
            return;
        }
        try {
            $status = $this->groupRepository->destroy($groupId);
            $status ? $presenter->onSuccess() : $presenter->onNotfound();
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
