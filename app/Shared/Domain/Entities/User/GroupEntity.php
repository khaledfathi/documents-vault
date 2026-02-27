<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\User;

class GroupEntity
{
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
}
