<?php
declare(strict_types=1);
namespace App\Shared\Domain\Entities\User;

class GroupPermissionEntity {
    public function __construct(
        public ?int $id = null, 
        public ?int $group_id = null, 
        public ?int $permission_id = null, 
        public ?GroupEntity $group = null, 
        public ?PermissionEntity $permission = null, 
    ) { }
}