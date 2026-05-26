<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Usecases;

use App\Features\Groups\Application\Contracts\ShowGroupContract;
use App\Features\Groups\Application\Outputs\ShowGroupOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use Exception;

final readonly class ShowGroupUsecase implements ShowGroupContract
{
    public function __construct(
        private GroupRepository $groupRepository,
        private CurrentUserContract $currentUser,
        private PermissionGateway $permissionGateway,
    ) {}
    public function execute(int $groupId, ShowGroupOutput $presenter): void
    {
        if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_GROUP)) {
            $presenter->onUnauthorized();
            return;
        }
        try {
            $groupEntity = $this->groupRepository->show($groupId);
            $groupEntity ? $presenter->onSuccess($groupEntity) : $presenter->onNotFound();
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
