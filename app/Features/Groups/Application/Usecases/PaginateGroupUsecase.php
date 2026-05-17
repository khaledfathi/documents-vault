<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Usecases;

use App\Features\Groups\Application\Contracts\PaginateGroupContract;
use App\Features\Groups\Application\Outputs\PaginateGroupOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use Exception;

final class PaginateGroupUsecase implements PaginateGroupContract
{
    public function __construct(
        private readonly GroupRepository $groupRepository,
        private readonly PermissionGateway $permissionGateway,
        private readonly CurrentUserContract $currentUser
    ) {}
    public function execute(PaginateGroupOutput $presenter, int $perPage = 10)
    {
        if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_GROUP)) {
            $presenter->onUnauthorized();
            return;
        }
        try {
            $pagination = $this->groupRepository->paginate($perPage);
            $presenter->onSuccess($pagination);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
