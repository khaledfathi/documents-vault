<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Contracts;

use App\Features\Categories\Application\Outputs\UpdateCategoryOutput;
use App\Shared\Domain\Entities\Document\CategoryEntity;

interface UpdateCategoryContract
{
    public function execute(CategoryEntity $categoryEntity, UpdateCategoryOutput $presenter): void;
}
