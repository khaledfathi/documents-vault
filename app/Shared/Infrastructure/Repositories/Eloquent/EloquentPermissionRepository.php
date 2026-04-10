<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\User\PermissionEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Repositories\PermissionRepository;
use App\Shared\Infrastructure\Models\Permission;

final class EloquentPermissionRepository implements PermissionRepository
{
    public function index(): array
    {
        $records =  Permission::all();
        $permissionEntities = [];
        foreach ($records as $record) {
            $permissionEntities[] = new PermissionEntity(
                id: $record->id,
                permissionType: PermissionType::from($record->permission),
            );
        }
        return $permissionEntities;
    }
}
