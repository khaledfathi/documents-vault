<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\User\GroupEntity;
use App\Shared\Domain\Entities\User\GroupPermissionEntity;
use App\Shared\Domain\Entities\User\PermissionEntity;
use App\Shared\Domain\Entities\User\PhoneEntity;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Repositories\UserRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Infrastructure\Models\User;

final class EloquentUserRepository implements UserRepository
{
    /**
     * @inheritdoc
     */
    public function paginate(int $perPage): EntitiesWithPagination
    {
        return new EntitiesWithPagination();
    }
    public function show(int $id): UserEntity
    {
        $record = User::with('phones', 'group', 'group.groupPermissions', 'group.groupPermissions.permission')->find($id)
            ->where('id', $id)->first();
        if ($record) {
            $phones = [];
            foreach ($record->phones as $phone) {
                $phones[] = new PhoneEntity(
                    id: $phone->id,
                    phone: $phone->phone,
                );
            }
            $permissions = [];
            foreach ($record->group->groupPermissions as $groupPermission) {
                $permissions[] = new GroupPermissionEntity(
                    id: $groupPermission->id,
                    groupId: $groupPermission->group_id,
                    permissionId: $groupPermission->permission_id,
                    group: new GroupEntity(
                        id: $groupPermission->group->id,
                        name: $groupPermission->group->name,
                    ),
                    permission: new PermissionEntity(
                        id: $groupPermission->permission->id,
                        permissionType: PermissionType::from($groupPermission->permission->permission),
                    )
                );
            }
            $group = new GroupEntity(
                id: $record->group->id,
                name: $record->group->name,
                permissions: $permissions,
            );
            return new UserEntity(
                id: $record->id,
                groupId: $record->group_id,
                name: $record->name,
                email: $record->email,
                password: $record->password,
                phones: $phones,
                group: $group,
            );
        }
        return new UserEntity();
    }
    public function store(UserEntity $userEntity): UserEntity
    {
        return new UserEntity();
    }
    public function update(UserEntity $userEntity): bool
    {
        return true;
    }
    public function destroy(int $int): bool
    {
        return true;
    }

}