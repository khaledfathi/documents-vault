<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Domain\Entities\Group\PermissionEntity;;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Repositories\GroupRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Infrastructure\Models\Group;
use App\Shared\Infrastructure\Repositories\Eloquent\Traits\PaginatorTrait;

final class EloquentGroupRepository implements GroupRepository
{
    use PaginatorTrait;

    /**
     * @var ADMIN_GROUP_ID the id of first group stored in the database
     */
    private const ADMIN_GROUP_ID = 1;
    /**
     * @var DEFAULT_GROUP the id of second group stored in the database
     */
    private const DEFAULT_GROUP_ID = 2;
    /**
     * @inheritdoc
     */
    public function paginate(int $perPage): EntitiesWithPagination
    {
        $records = Group::with('groupPermissions', 'groupPermissions.permission')->paginate($perPage);
        $groupEntities = [];
        foreach ($records as $record) {
            //permissions
            $permissions = [];
            foreach ($record->groupPermissions as $groupPermission) {
                $permission = $groupPermission->permission;
                $permissions[] = new PermissionEntity(
                    id: $permission->id,
                    permissionType: PermissionType::from($permission->permission),
                );
            }
            $groupEntities[] = new GroupEntity(
                id: $record->id,
                name: $record->name,
                permissions: $permissions,
                isProtected:$this->isProtected($record->id),
            );
        }
        //pagination
        $pagination = $this->mapPaginator($records, $perPage);
        //
        return new EntitiesWithPagination(
            pagination:$pagination,
            entities:$groupEntities,
        );
    }
    public function show(int $groupId): ?GroupEntity
    {
        $record = Group::with('groupPermissions', 'groupPermissions.permission')->find($groupId);
        if (! $record) return null;
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
            isProtected:$this->isProtected($record->id),
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
                isProtected:$this->isProtected($record->id),
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
        $record = Group::find($groupEntity->id);
        if(! $record) return false;
        $record->update([
            'name' => $groupEntity->name,
        ]);
        $record->groupPermissions()->delete();
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
        $groupEntity->isProtected = $this->isProtected($record->id);
        $groupEntity->permissions = $record->groupPermissions->map(function ($groupPermission) {
            $permission = $groupPermission->permission;
            return new PermissionEntity(
                id: $permission->id,
                permissionType: PermissionType::from($permission->permission),
            );
        })->all();
        return true;
    }
    public function destroy(int $groupId): bool
    {
        return Group::find($groupId)?->delete() ? true : false;
    }
    public function getAdminGroupId(): int{
        return (int) self::ADMIN_GROUP_ID;
    }
    public function getDefaultGroupId(): int{
        return (int) self::DEFAULT_GROUP_ID;
    }

    /**
    * check if the 'id' is [ADMIN_GROUP_ID] or [DEFAULT_GROUP_ID]
    */
    private function isProtected(int $id):bool{
        return $id === self::ADMIN_GROUP_ID || $id === self::DEFAULT_GROUP_ID;
    }
}
