<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Outputs;

use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Domain\Entities\Document\CategoryEntity;

interface PaginateCategoryOutput
{
    /**
     * @param EntitiesWithPagination<CategoryEntity> $entitiesWithPagination
     * @return void
     */
    public function onSuccess(EntitiesWithPagination $entitiesWithPagination): void;
    public function onUnauthorized(): void;
    public function onFailure(string $error): void;
}
