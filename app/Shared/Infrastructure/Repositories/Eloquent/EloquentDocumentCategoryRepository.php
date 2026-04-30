<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\Document\DocumentCategoryEntity;
use App\Shared\Domain\Repositories\DocumentCategoryRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;

final class EloquentDocumentCategoryRepository implements DocumentCategoryRepository
{
    public function paginate(int $perPage = 10): EntitiesWithPagination{
        return new EntitiesWithPagination();
    }
    public function show(int $documentCategoryId): ?DocumentCategoryEntity{
        return new DocumentCategoryEntity();
    }
    public function store(DocumentCategoryEntity $documentCategoryEntity): DocumentCategoryEntity{
        return new DocumentCategoryEntity();
    }
    public function update(DocumentCategoryEntity $documentCategoryEntity): bool{
        return false;
    }
    public function destroy(int $documentCategoryId): bool{
        return false;
    }
}
