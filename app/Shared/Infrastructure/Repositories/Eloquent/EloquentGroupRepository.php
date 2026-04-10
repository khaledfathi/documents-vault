<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\User\GroupEntity;
use App\Shared\Domain\Entities\User\PermissionEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Repositories\GroupRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Infrastructure\Models\Group;

final class EloquentGroupRepository implements GroupRepository
{
    public function index(): EntitiesWithPagination
    {
        return new EntitiesWithPagination();
    }
    public function show(int $groupId): ?GroupEntity
    {
        $record = Group::with('groupPermissions', 'groupPermissions.permission')->find($groupId);
        if (! $record ) return null;
        //
        $permissionEntities = [];
        foreach ($record->groupPermissions as $groupPermission) {
            $permissionEntities[] = new PermissionEntity(
                id: $groupPermission->permission->id,
                permissionType: PermissionType::from($groupPermission->permission->permission),
            );
        }
        //
        return new GroupEntity(
            id: $record->id,
            name: $record->name,
            permissions: $permissionEntities,
        );
    }
    public function showByUserId(int $userId): ?GroupEntity
    {
        $record = Group::with('groupPermissions', 'groupPermissions.permission')
            ->leftJoin('users', 'users.id', '=', $userId)->select('groups.*')->first();
        if ($record->groupPermissions) {
            $permissionEntities = [];
            foreach ($record->groupPermissions as $groupPermission) {
                $permissionEntities[] = new PermissionEntity(
                    id: $groupPermission->permission->id,
                    permissionType: PermissionType::from($groupPermission->permission->permission),
                );
            }
            return new GroupEntity(
                id: $record->id,
                name: $record->name,
                permissions: $permissionEntities,
            );
        }
        return null;
    }
    public function store(GroupEntity $groupEntity): GroupEntity
    {
        $record = Group::create([
            'name' => $groupEntity->name,
        ]);
        $record->groupPermissions()->createMany(
            array_map(fn($permission) => [
                "group_id" => $record->id,
                "permission_id" => $permission->id,
            ], $groupEntity->permissions)
        );
        //data result
        $record->load('groupPermissions.permission');
        //
        $groupEntity->id = $record->id;
        $groupEntity->permissions = $record->groupPermissions->map(function ($groupPermission) {
            $permission = $groupPermission->permission;
            return new PermissionEntity(
                id: $permission->id,
                permissionType: PermissionType::from($permission->permission),
            );
        })->all();
        return $groupEntity;
    }
    public function update(GroupEntity $groupEntity): bool
    {
        return false;
    }
    public function destroy(int $groupId): bool
    {
        return Group::find($groupId)?->delete() ? true : false;
    }
}
