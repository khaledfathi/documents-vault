<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repositories;

use App\Shared\Domain\Entities\User\PermissionEntity;

interface PermissionRepository
{
    /**
     * @return array<PermissionEntity>
     */
    public function index(): array;
}
