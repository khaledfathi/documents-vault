<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Contracts;

use App\Features\Groups\Application\Outputs\ShowGroupOutput;

interface ShowGroupContract
{
    public function execute(int $groupId, ShowGroupOutput $presenter): void;
}
