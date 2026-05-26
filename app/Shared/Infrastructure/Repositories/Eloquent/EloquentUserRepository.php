<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Domain\Entities\Group\PermissionEntity;
use App\Shared\Domain\Entities\User\PhoneEntity;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Repositories\UserRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Infrastructure\Models\Group;
use App\Shared\Infrastructure\Models\User;
use App\Shared\Infrastructure\Repositories\Eloquent\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

final class EloquentUserRepository implements UserRepository
{
    use PaginatorTrait;

    /**
     * @var ROOT_USER_ID the id of first user created in the system
     */
    private const ROOT_USER_ID = 1;
    /**
     * @inheritdoc
     */
    public function paginate(int $perPage = 10): EntitiesWithPagination
    {
        $records = User::with('phones', 'group')->paginate($perPage);
        //entities
        $userEntities = [];
        foreach ($records as $record) {
            //group
            $group = new GroupEntity(
                id: $record->group->id,
                name: $record->group->name,
            );
            //phones
            $phones = [];
            foreach ($record->phones ?? [] as $phone) {
                $phones[] = new PhoneEntity(
                    id: $phone->id,
                    userId: $record->id,
                    phone: $phone->phone,
                );
            }
            //user
            $userEntities[] = new UserEntity(
                id: $record->id,
                groupId: $record->group_id,
                name: $record->name,
                email: $record->email,
                phones: $phones,
                group: $group,
                isRoot: $record->id == self::ROOT_USER_ID ? true : false,
            );
        }
        //pagination
        $pagination = $this->mapPaginator($records, $perPage);
        //
        return new EntitiesWithPagination($pagination, $userEntities);
    }
    public function findByEmail(string $email): ?UserEntity
    {
        $record = User::with('phones', 'group', 'group.groupPermissions', 'group.groupPermissions.permission')
            ->where('email', $email)->first();
        if ($record) {
            $phones = $this->generatePhoneEntities($record->phones);
            $groupPermissions = $this->generatePermissionEntities($record->group->groupPermissions);
            $group =  $this->generateGroupEntity($record->group, $groupPermissions);

            return new UserEntity(
                id: $record->id,
                groupId: $record->group_id,
                name: $record->name,
                email: $record->email,
                password: $record->password,
                phones: $phones,
                group: $group,
                isRoot: $record->id == self::ROOT_USER_ID ? true : false,
            );
            return new UserEntity();
        }
        return null;
    }
    public function show(int $id): ?UserEntity
    {
        $record = User::with('phones', 'group', 'group.groupPermissions', 'group.groupPermissions.permission')
            ->where('id', $id)->first();
        if ($record) {
            $phones = $this->generatePhoneEntities($record->phones);
            $groupPermissions = $this->generatePermissionEntities($record->group->groupPermissions);
            $group =  $this->generateGroupEntity($record->group, $groupPermissions);

            $userEntity = new UserEntity(
                id: $record->id,
                groupId: $record->group_id,
                name: $record->name,
                email: $record->email,
                password: $record->password,
                phones: $phones,
                group: $group,
                isRoot: $record->id == self::ROOT_USER_ID ? true : false,
            );
            return $userEntity;
        }
        return null;
    }
    public function store(UserEntity $userEntity): UserEntity
    {
        $record = User::create([
            'group_id' => $userEntity->groupId,
            'name' => $userEntity->name,
            'email' => $userEntity->email,
            'password' => Hash::make($userEntity->password),
        ]);
        //phones (NOTES : createMany() method know the user_id and automatic create the timestamp)
        $phoneModels = $record->phones()->createMany(
            array_map(fn($phone) => [
                'phone'   => $phone->phone,
            ], $userEntity->phones)
        );
        $userEntity->phones = $phoneModels->map(
            fn($phone)
            => new PhoneEntity(
                id: $phone->id,
                userId: $phone->userId,
                phone: $phone->phone
            )
        )->toArray();
        //
        $userEntity->id = $record->id;
        $userEntity->isRoot =  $record->id == self::ROOT_USER_ID ? true : false;
        return $userEntity;
    }
    /**
     * Update user record ( full replacement )
     * @param UserEntity $userEntity
     * @return bool true if update , false if record not found
     */
    public function update(UserEntity $userEntity): bool
    {
        $data = [];
        $userEntity->name ? $data['name'] = $userEntity->name : null;
        $userEntity->email ? $data['email'] = $userEntity->email : null;
        $userEntity->password ? $data['password'] = Hash::make($userEntity->password) : null;
        $userEntity->groupId ? $data['group_id'] = $userEntity->groupId : null;

        $record = User::find($userEntity->id);
        if (!$record) {
            return false;
        }
        //delete old phones
        $record->phones()->delete();
        //replace phones
        $record->phones()->createMany(
            array_map(fn($phone) => [
                'phone'   => $phone->phone,
            ], $userEntity->phones ?? [])
        );

        return $record->update($data);
    }
    public function destroy(int $id): bool
    {
        return User::find($id)?->delete() ? true : false;
    }
    /**
     * @param \App\Shared\Infrastructure\Models\Group $group ,
     * @param array<\App\Shared\Domain\Entities\Group\PermissionEntity;> $permissions
     * @return GroupEntity
     */
    private function generateGroupEntity(Group $group, array $permissions = []): GroupEntity
    {
        return new GroupEntity(
            id: $group->id,
            name: $group->name,
            permissions: $permissions
        );
    }

    /**
     * @param Collection $groupPermission
     * @return array <\App\Shared\Domain\Entities\Group\PermissionEntity;>
     */
    private function generatePermissionEntities(Collection $groupPermisssions): array
    {
        $permissions = [];
        foreach ($groupPermisssions as $groupPermission) {
            $permissions[] = new PermissionEntity(
                id: $groupPermission->permission->id,
                permissionType: PermissionType::from($groupPermission->permission->permission),
            );
        };
        return $permissions;
    }
    /**
     * @param Collection $phones
     * @param array<\App\Shared\Infrastructure\Models\Phone>
     */
    private function generatePhoneEntities(Collection $phones): array
    {
        $phoneEntities = [];
        foreach ($phones as $phone) {
            $phoneEntities[] = new PhoneEntity(
                id: $phone->id,
                userId: $phone->user_id,
                phone: $phone->phone,
            );
        }
        return $phoneEntities;
    }
    public function getPermissions(int $userId): array
    {
        $record = User::with(['group.groupPermissions.permission'])->where('id', $userId)->first();
        if (! $record?->group?->groupPermissions) {
            return [];
        }
        return $this->generatePermissionEntities($record->group->groupPermissions);
    }
    public function getRootUserId(): int
    {
        return (int) self::ROOT_USER_ID;
    }
    public static function isRoot(int $id): bool
    {
        return $id === self::ROOT_USER_ID;
    }
}
