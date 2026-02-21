<?php
declare(strict_types=1);
namespace App\Shared\Domain\Entities\User;

class GroupPermissionEntity {
    public function __construct(
        public ?int $id , 
        public ?int $group_id, 
        public ?int $permission_id, 
    ) { }
}