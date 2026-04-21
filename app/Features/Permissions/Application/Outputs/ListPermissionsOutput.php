<?php

declare(strict_types=1);

namespace App\Features\Permissions\Application\Outputs;

use App\Shared\Domain\Entities\Group\PermissionEntity;;

interface ListPermissionsOutput
{
    /**
     * @param array<PermissionEntity> $permissionEntities
     */
    public function onSuccess(array $permissionEntities): void;
    public function onUnauthorized(): void;
    public function onFailure(string $error): void;
}
