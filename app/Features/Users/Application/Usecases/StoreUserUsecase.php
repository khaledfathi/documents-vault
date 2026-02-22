<?php
declare (strict_types=1);

namespace App\Features\Users\Application\Usecases;

use App\Features\Users\Application\Contracts\StoreUserContract;
use App\Features\Users\Application\Outputs\StoreUserOutput;

class StoreUserUsecase implements StoreUserContract{
    public function execute (int $currentUserId, StoreUserOutput $presenter){
        
    }
}