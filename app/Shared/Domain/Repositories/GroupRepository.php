<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repositories;

use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;

interface GroupRepository
{
    /**
     * @return EntitiesWithPagination<GroupEntity>
     */
    public function paginate(int $perPage): EntitiesWithPagination;
    public function show(int $groupId): ?GroupEntity;
    public function showByUserId(int $userId): ?GroupEntity;
    public function store(GroupEntity $groupEntity): GroupEntity;
    public function update(GroupEntity $groupEntity): bool;
    public function destroy(int $groupId): bool;
    public function getAdminGroupId(): int;
    public function getDefaultGroupId(): int;
}
