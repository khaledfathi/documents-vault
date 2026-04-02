<?php
declare (strict_types=1);
namespace App\Shared\Domain\Entities\User;

final class UserEntity{

    /**
     * Summary of __construct
     * @param ?null $id
     * @param  ?int $groupId
     * @param  ?string $name
     * @param  ?string $email
     * @param  ?string $password
     * @param  ?array<?\App\Shared\Domain\Entities\User\PhoneEntity> $phones,
     * @param  ?\App\Shared\Domain\Entities\User\GroupEntity $group
     */
    public function __construct(
        public ?int $id = null,
        public ?int $groupId = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?array $phones = null,
        public ?\App\Shared\Domain\Entities\User\GroupEntity$group = null,
    ) { }

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
    public function toArray ():array{
        return [
            "id"=> $this->id,
            "name"=> $this->name,
            "email"=> $this->email,
            "phones"=>$this->phones ? $this->getPhoneNumbers() : null,
            "group"=> $this->group ? [
                "id"=> $this->group->id,
                "name" => $this->group->name,
            ] : null,
            "permissions"=> $this->group ? $this->permissionsAsArray() : null,
        ];
    }

    private function getPhoneNumbers ():array {
        $phones=[];
        foreach ($this->phones as $phone) {
            $phones[] = [ 'id' => $phone->id , 'phone'=> $phone->phone];
        }
        return $phones;
    }
    private function permissionsAsArray ():array{
        $permissions = [];
        foreach ($this->group?->permissions ?? [] as $permission) {
            $permissions[] = [
                "id"=> $permission->id,
                "permission"=> $permission->permissionType->value
            ];
        }
        return $permissions;
    }
}
