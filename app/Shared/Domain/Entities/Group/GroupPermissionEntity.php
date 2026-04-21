<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\Group;

final class GroupPermissionEntity
{
    public function __construct(
        public ?int $id = null,
        public ?int $groupId = null,
        public ?int $permissionId = null,
        public ?GroupEntity $group = null,
        public ?PermissionEntity $permission = null,
    ) {
    }
}
