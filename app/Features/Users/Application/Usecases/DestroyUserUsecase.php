<?php

declare(strict_types=1);

namespace App\Features\Users\Application\Usecases;

use App\Features\Users\Application\Contracts\DestroyUserContract;
use App\Features\Users\Application\Outputs\DestroyUserOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\UserRepository;
use Exception;

final class DestroyUserUsecase implements DestroyUserContract
{
    public function __construct(
        private readonly CurrentUserContract $currentUser,
        private readonly PermissionGateway $permissionGateway,
        private readonly UserRepository $userRepository,
    ) {}
    public function execute(int $userId, DestroyUserOutput $presenter): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::DELETE_USER)) {
                $presenter->onUnauthorized();
                return;
            }
            $record = $this->userRepository->show($userId);
            if (! $record) {
                $presenter->onNotFound();
                return;
            }
            //prevent destroy root user
            if ($record->isRoot) {
                $presenter->onRoorUser();
                return;
            }
            $this->userRepository->destroy($userId);
            $presenter->onSuccess();
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
