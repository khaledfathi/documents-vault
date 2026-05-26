<?php

declare(strict_types=1);

namespace App\Features\Users\Application\Usecases;

use App\Features\Users\Application\Contracts\StoreUserContract;
use App\Features\Users\Application\Outputs\StoreUserOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use App\Shared\Domain\Repositories\UserRepository;
use Exception;

final readonly class StoreUserUsecase implements StoreUserContract
{
    public function __construct(
        private UserRepository $userRepository,
        private GroupRepository $groupRepository,
        private PermissionGateway $permissionGateway,
        private CurrentUserContract $currentUser,
    ) {}
    public function execute(UserEntity $userEntity, StoreUserOutput $presenter): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::CREATE_USER)) {
                $presenter->onUnauthorized();
                return;
            }
            if (! $userEntity->groupId) $userEntity->groupId = $this->groupRepository->getDefaultGroupId();
            $userRecord = $this->userRepository->store($userEntity);
            $userEntity->id = $userRecord->id;

            $groupRecord = $this->groupRepository->showByUserId($userEntity->id ?? 0);
            $userEntity->group = $groupRecord;

            $presenter->onSuccess($userEntity);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
