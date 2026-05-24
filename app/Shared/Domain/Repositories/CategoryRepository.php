<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repositories;

use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;

interface CategoryRepository
{
    /**
     * @return EntitiesWithPagination<CategoryEntity>
     */
    public function paginate(int $perPage = 10): EntitiesWithPagination;
    public function show(int $categoryId): ?CategoryEntity;
    public function store(CategoryEntity $categoryEntity): CategoryEntity;
    public function update(CategoryEntity $categoryEntity): bool;
    public function destroy(int $categoryId): bool;
    public function getDefaultGroupId(): int;
    public function isDefaultGroup(int $groupId): bool;
}
