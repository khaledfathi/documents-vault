<?php

declare(strict_types=1);

namespace App\Features\Users\Application\Contracts;

use App\Features\Users\Application\Outputs\DestroyUserOutput;

interface DestroyUserContract
{
    public function execute(int $userId, DestroyUserOutput $presenter): void;
}
