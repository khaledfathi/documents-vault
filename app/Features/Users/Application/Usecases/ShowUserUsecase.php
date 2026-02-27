<?php
declare (strict_types=1);

namespace App\Features\Users\Application\Usecases;

use App\Features\Users\Application\Contracts\ShowUserContract;
use App\Features\Users\Application\Outputs\ShowUserOutput;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\UserRepository;
use Exception;

final class ShowUserUsecase implements ShowUserContract {

    public function __construct(
        private readonly UserRepository $userRepository, 
        private readonly PermissionGateway $permissionGateway,
    ) { }
    public function execute (int $currentUserId, int $userId , ShowUserOutput $presenter){
        try {
            if ( ! $this->permissionGateway->can($currentUserId, PermissionType::VIEW_USER ) ){
                $presenter->onUnauthorized();
            } ;
            $userEntity = $this->userRepository->show($userId);
            if(! $userEntity) return $presenter->onNotFound();
            $presenter->onSuccess($userEntity);
        }catch(Exception $e){
            $presenter->onFailure($e->getMessage());     
        }
    } 
}