<?php
declare(strict_types=1);

namespace  App\Features\Users\Application\Contracts;

use App\Features\Users\Application\Outputs\StoreUserOutput;

interface StoreUserContract{
    public function execute (int $currentUserId, StoreUserOutput $presenter); 
}