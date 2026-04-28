<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Contracts;

use App\Features\Categories\Application\Outputs\ShowCategoryOutput;

interface ShowCategoryContract
{
    public function execute(int $categoryId, ShowCategoryOutput $presenter): void;
}
