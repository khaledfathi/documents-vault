<?php

declare(strict_types=1);

namespace App\Features\Users\Application\Usecases;

use App\Features\Users\Application\Contracts\UpdateUserContract;
use App\Features\Users\Application\Outputs\UpdateUserOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\UserRepository;
use Exception;

final class UpdateUserUsecase implements UpdateUserContract
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PermissionGateway $permissionGateway,
        private readonly CurrentUserContract $currentUser,
    ) {
    }
    public function execute(UserEntity $userEntity, UpdateUserOutput $presenter)
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::EDIT_USER)) {
                $presenter->onUnauthorized();
                return ;
            }
            $status = $this->userRepository->update($userEntity);
            $status ? $presenter->onSuccess($status) : $presenter->onNotFound();
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
