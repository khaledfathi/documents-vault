<?php
declare(strict_types=1);
namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\User\GroupEntity;
use App\Shared\Domain\Entities\User\PermissionEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Repositories\GroupRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Infrastructure\Models\Group;

final class EloquentGroupRepository implements  GroupRepository {
    public function index():EntitiesWithPagination{
        return new EntitiesWithPagination();
    }
    public function show (int $groupId):GroupEntity|null{
        return new GroupEntity();
    }
    public function showByUserId(int $userId):GroupEntity|null{
        $record = Group::with('groupPermissions','groupPermissions.permission')
            ->leftJoin('users', 'users.id', '=', $userId)->select('groups.*')->first();
        if($record->groupPermissions){
            $permissionEntities = [];
            foreach($record->groupPermissions as $groupPermission){
                $permissionEntities[] = new PermissionEntity(
                    id:$groupPermission->permission->id,
                    permissionType:PermissionType::from($groupPermission->permission->permission),
                );
            }
            return new GroupEntity(
                id:$record->id,
                name:$record->name,
                permissions:$permissionEntities,
            );
        }
        return null;
    }
    public function store(GroupEntity $groupEntity):GroupEntity{
        return new GroupEntity();
    }
    public function update(GroupEntity $groupEntity):bool{
        return false;
    }
    public function delete(int $groupId):bool{
        return false;
    }
}
