<?php
declare(strict_types=1);

namespace App\Shared\Domain\Entities\User;

use App\Shared\Domain\Enums\User\PermissionType;

class PermissionEntity {
    public function __construct(
        public ?int $id, 
        public ?PermissionType $permission, 
    ) { }
}