<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Contracts;

use App\Features\Categories\Application\Outputs\PaginateCategoryOutput;

interface PaginateCategoryContract
{
    public function execute(PaginateCategoryOutput $presenter, int $perPage = 10): void;
}
