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
     * @param  ?array<PhoneEntity> $phones
     */
    public function __construct(
        public ?int $id, 
        public ?int $groupId, 
        public ?string $name, 
        public ?string $email, 
        public ?string $password, 
        public ?array $phones, 
    ) { }
}