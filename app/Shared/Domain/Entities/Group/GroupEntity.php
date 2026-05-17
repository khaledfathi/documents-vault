<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\Group;

use App\Shared\Domain\Enums\User\PermissionType\PermissionEntity;

final class GroupEntity
{
    /**
     * @param ?int $id
     * @param ?string $name
     * @param ?array<PermissionEntity> $permissions
     * @param bool $isDefault is this group has id [DEFAULT_GROUP_ID]
     */
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?array $permissions = null,
        public  bool $isProtected = false,
        public  bool $isAdmin = false,
    ) {}
    public function toArray()
    {
        $data = [
            "id" => $this->id,
            "name" => $this->name,
        ];
        if ($this->permissions) {
            $permissions = [];
            foreach ($this->permissions as $permission) {
                $permissions[] = [
                    'id' => $permission->id,
                    'permission' => $permission->permissionType->value,
                ];
            }
            $data['permissions'] = $permissions;
        }
        return $data;
    }
}
