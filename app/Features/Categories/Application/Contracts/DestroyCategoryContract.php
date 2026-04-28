<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Contracts;

use App\Features\Categories\Application\Outputs\DestroyCategoryOutput;

interface DestroyCategoryContract
{
    public function execute(int $categoryId, DestroyCategoryOutput $presneter): void;
}
