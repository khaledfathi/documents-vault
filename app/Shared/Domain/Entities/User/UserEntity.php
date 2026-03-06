<?php
declare (strict_types=1);
namespace App\Shared\Domain\Entities\User;

use App\Shared\Domain\Enums\User\PermissionType;
use Dom\Element; 


class UserEntity{
    /**
     * Summary of __construct
     * @param ?null $id
     * @param  ?int $groupId
     * @param  ?string $name
     * @param  ?string $email
     * @param  ?string $password
     * @param  ?array<PhoneEntity> $phones,
     * @param  ?GroupEntity $group
     */
    public function __construct(
        public ?int $id = null,
        public ?int $groupId = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?array $phones = null,
        public ?GroupEntity $group = null, 
    ) { }

    public function toArray ():array{ 
        return [
            "id"=> $this->id,
            "name"=> $this->name,
            "email"=> $this->email,
            "phones"=> $this->getPhoneNumbers() ,
            "group"=> [ 
                $this->group->id,
                $this->group->name,
            ],
            "permissions"=> $this->permissionsAsArray(),
        ];
    }

    private function getPhoneNumbers ():array { 
        $phones=[]; 
        foreach ($this->phones as $phone) { 
            $phones[] = $phone->phone;
        }
        return $phones;
    }
    private function permissionsAsArray ():array{
        $permissions = []; 
        foreach ($this->group->permissions as $permission) {
            $permissions[] = [ 
                "id"=> $permission->id,
                "permission"=> $permission->permission->permissionType->value,
            ];
        }
        return $permissions;
    }
}