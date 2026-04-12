<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Contracts;

use App\Features\Groups\Application\Outputs\PaginateGroupOutput;

interface PaginateGroupContract
{
    public function execute(PaginateGroupOutput $presenter, int $perPage = 10);
}
