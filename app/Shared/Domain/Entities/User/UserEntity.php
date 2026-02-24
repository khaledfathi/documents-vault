<?php
declare (strict_types=1);
namespace App\Shared\Domain\Entities\User; 


class UserEntity{
    /**
     * Summary of __construct
     * @param ?null $id
     * @param  ?int $groupId
     * @param  ?string $name
     * @param  ?string $email
     * @param  ?string $password
     * @param  ?array<PhoneEntity> $phones,
     * @param  ?array<GroupEntity> $groups
     */
    public function __construct(
        public ?int $id = null,
        public ?int $groupId = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?array $phones = null,
        public ?array $groups = null, 
    ) { }
}