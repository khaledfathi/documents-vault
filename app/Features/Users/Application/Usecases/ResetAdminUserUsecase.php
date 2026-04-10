<?php

declare(strict_types=1);

namespace App\Features\Users\Application\Usecases;

use App\Features\Users\Application\Contracts\ResetAdminUserContract;
use App\Features\Users\Application\Outputs\ResetAdminUserOutput;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Entities\User\GroupEntity;
use App\Shared\Domain\Repositories\UserRepository;
use Exception;

final class ResetAdminUserUsecase implements ResetAdminUserContract
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }
    public function execute(ResetAdminUserOutput $presenter)
    {
        try {
            $this->userRepository->update(new UserEntity(
                id: UserEntity::ADMIN_ID,
                groupId: GroupEntity::ADMIN_GROUP_ID,
                name: 'admin',
                email: 'admin@mail.com',
                password: 'admin'
            ));
            $presenter->onSuccess();
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
