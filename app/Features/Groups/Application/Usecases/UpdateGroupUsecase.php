<?php

namespace App\Features\Groups\Application\Usecases;

use App\Features\Groups\Application\Contracts\UpdateGroupContract;
use App\Features\Groups\Application\Outputs\UpdateGroupOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use Exception;

final readonly class UpdateGroupUsecase implements UpdateGroupContract
{
    public function __construct(
        private GroupRepository $groupRepository,
        private PermissionGateway $permissionGatewaty,
        private CurrentUserContract $currentUser,
    ) {}
    public function execute(GroupEntity $groupEntity, UpdateGroupOutput $presenter): void
    {
        try {
            if (! $this->permissionGatewaty->can($this->currentUser->id(), PermissionType::EDIT_GROUP)) {
                $presenter->onUnauthorized();
                return;
            }
            $record = $this->groupRepository->show($groupEntity->id);
            if (!$record) {
                $presenter->onNotFound();
                return;
            }
            if ($record->isAdmin) {
                $presenter->onAdminGroup();
                return;
            }
            $this->groupRepository->update($groupEntity);
            $presenter->onSuccess();
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
