<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Gateways;

use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\UserRepository;

final readonly class UserPermissionGateway implements PermissionGateway
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}
    public function can(int $userId, PermissionType $ability): bool
    {
        $permissions = $this->userRepository->getPermissions($userId);
        if (!$permissions) {
            return false;
        }
        foreach ($permissions as $permission) {
            if ($permission->permissionType == $ability) {
                return true;
            }
        }
        return false;
    }
}
