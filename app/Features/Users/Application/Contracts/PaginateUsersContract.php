<?php
declare (strict_types=1);

namespace App\Features\Users\Application\Contracts; 

use App\Features\Users\Application\Outputs\PaginateUsersOutput;

interface PaginateUsersContract{
    public function execute ( int $currentUserId , PaginateUsersOutput $presenter, int $perPage = 10); 
}