<?php
declare(strict_types=1);
namespace App\Shared\Infrastructure\Gateways; 

use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\UserRepository;

final class UserPermissionGateway implements PermissionGateway{
    public function __construct(
        private readonly UserRepository $userRepository, 
    ) { }
    public function can (int $userId , PermissionType $ability):bool{
        $user = $this->userRepository->show($userId);
        if (!$user) return false ; 
        foreach($user->group->permissions as $groupPermission){
            if ($groupPermission->permission->permissionType == $ability) return true;
        }
        return false; 
    }
}