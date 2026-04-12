<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\User;

use App\Shared\Domain\Entities\Group\GroupEntity;

final class UserEntity
{
    public const ROOT_USER_ID = 1;
    /**
     * Summary of __construct
     * @param ?null $id
     * @param  ?int $groupId
     * @param  ?string $name
     * @param  ?string $email
     * @param  ?string $password
     * @param  ?array<?\App\Shared\Domain\Entities\User\PhoneEntity> $phones,
     * @param  ?\App\Shared\Domain\Entities\Group\GroupEntity $group
     */
    public function __construct(
        public ?int $id = null,
        public ?int $groupId = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?array $phones = null,
        public ?GroupEntity $group = null,
    ) {}
    public static function isRootUser(int $userId)
    {
        return $userId == self::ROOT_USER_ID;
    }
    /**
     * @return array{
     * id: int,
     * name: string,
     * email: string.
     * phones: array<array{ id: int, phone: string}>|null,
     * group: array {id:int , name:string}|null,
     * permissions: array{id: int , permission: string}|null
     * }
     * */
    public function toArray(): array
    {
        $data = [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "phones" => $this->phones ? $this->getPhoneNumbers() : [],
            "group" => $this->group ? [
                "id" => $this->group->id,
                "name" => $this->group->name,
            ] : null,
        ];
        $permissions = $this->group ? $this->permissionsAsArray() : null;
        if ($permissions) {
            $data["permissions"] = $permissions;
        }
        return $data;
    }
    public function isRoorUser(): bool
    {
        return $this->id == self::ROOT_USER_ID;
    }

    private function getPhoneNumbers(): array
    {
        $phones = [];
        foreach ($this->phones as $phone) {
            $phones[] = ['id' => $phone->id, 'phone' => $phone->phone];
        }
        return $phones;
    }
    private function permissionsAsArray(): array
    {
        $permissions = [];
        foreach ($this->group?->permissions ?? [] as $permission) {
            $permissions[] = [
                "id" => $permission->id,
                "permission" => $permission->permissionType->value,
            ];
        }
        return $permissions;
    }
}
