<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\User;
use App\Shared\Domain\Enums\User\PermissionType\PermissionEntity;

final class GroupEntity
{
    /**
     * @param ?int $id
     * @param ?string $name
     * @param ?array<\app\Shared\Domain\Entities\User\PermissionEntity> $permissions
     */
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?array $permissions = null,
    ) {}
}
