<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\User;

use App\Shared\Domain\Enums\User\PermissionType\PermissionEntity;

final class GroupEntity
{
    public const ADMIN_GROUP_ID = 1;
    public const READER_GROUP_ID = 2;
    /**
     * @param ?int $id
     * @param ?string $name
     * @param ?array<PermissionEntity> $permissions
     */
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?array $permissions = null,
    ) {}
    public static function isDefaultGroup(int $groupId)
    {
        return ( $groupId == self::ADMIN_GROUP_ID || $groupId  == self::READER_GROUP_ID);
    }
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
