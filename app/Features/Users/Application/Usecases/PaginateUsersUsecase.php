<?php
declare (strict_types=1);

namespace App\Features\Users\Application\Usecases;

use App\Features\Users\Application\Contracts\PaginateUsersContract;
use App\Features\Users\Application\Outputs\PaginateUsersOutput;
use App\Shared\Domain\Repositories\UserRepository;
use Exception;

final class PaginateUsersUsecase implements PaginateUsersContract {

    public function __construct(
        private readonly UserRepository $userRepository,
    ) { }
    public function execute ( int $currentUserId , PaginateUsersOutput $presenter , int $perPage = 10 ){
       try{
            $this->userRepository->paginate($perPage); 
       } catch (Exception $e){
            $presenter->onFailure($e->getMessage());
       }
    }
}