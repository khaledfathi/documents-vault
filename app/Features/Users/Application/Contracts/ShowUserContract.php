<?php
declare (strict_types=1);

namespace App\Features\Users\Application\Contracts;

use App\Features\Users\Application\Outputs\ShowUserOutput;

interface ShowUserContract {
    public function execute (int $currentUserId, int $userId , ShowUserOutput $presenter); 
}