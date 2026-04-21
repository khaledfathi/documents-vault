<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Contracts;

use App\Features\Groups\Application\Outputs\DestroyGroupOutput;

interface DestroyGroupContract
{
    public function execute(int $groupId, DestroyGroupOutput $presenter): void;
}
