<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repositories;

use App\Shared\Domain\Entities\Document\DocumentCategoryEntity;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;

interface DocumentCategoryRepository
{
    /**
     * @return EntitiesWithPagination<DocumentCategoryEntity>
     */
    public function paginate(int $perPage = 10): EntitiesWithPagination;
    public function show(int $documentCategoryId): ?DocumentCategoryEntity;
    public function store(DocumentCategoryEntity $documentCategoryEntity): DocumentCategoryEntity;
    public function update(DocumentCategoryEntity $documentCategoryEntity): bool;
    public function destroy(int $documentCategoryId): bool;
}
