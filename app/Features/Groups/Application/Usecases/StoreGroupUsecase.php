<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Usecases;

use App\Features\Groups\Application\Contracts\StoreGroupContract;
use App\Features\Groups\Application\Outputs\StoreGroupOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use Exception;

final readonly class StoreGroupUsecase implements StoreGroupContract
{
    public function __construct(
        private GroupRepository $groupRepository,
        private CurrentUserContract $currentUser,
        private PermissionGateway $permissionGateway,
    ) {}
    public function execute(GroupEntity $groupEntity, StoreGroupOutput $presenter)
    {
        if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::CREATE_GROUP)) {
            $presenter->onUnauthorized();
            return;
        };
        try {
            $record = $this->groupRepository->store($groupEntity);
            $presenter->onSuccess($record);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
