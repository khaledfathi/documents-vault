<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repositories;

use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Entities\Group\PermissionEntity;;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;

interface UserRepository
{
    /**
     * @param int $perPage
     * @return EntitiesWithPagination<UserEntity>
     */
    public function paginate(int $perPage = 10): EntitiesWithPagination;
    public function findByEmail(string $email): ?UserEntity;
    public function show(int $id): ?UserEntity;
    public function store(UserEntity $userEntity): UserEntity;
    public function update(UserEntity $userEntity): bool;
    public function destroy(int $id): bool;
    /**
     * @param int $userId
     * @return array<PermissionEntity>;
     */
    public function getPermissions(int $userId): array;
    public function getRootUserId(): int;
}
