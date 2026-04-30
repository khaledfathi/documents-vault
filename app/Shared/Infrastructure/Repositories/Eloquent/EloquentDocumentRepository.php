<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Repositories\DocumentRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Infrastructure\Models\Document;

final class EloquentDocumentRepository implements DocumentRepository
{
    public function paginate(int $perPage = 10): EntitiesWithPagination
    {
        return new EntitiesWithPagination();
    }
    public function show(int $documentId): ?DocumentEntity
    {
        return new DocumentEntity();
    }
    public function store(DocumentEntity $documentEntity): DocumentEntity
    {
        $record = Document::create([
            'user_id' => $documentEntity->userId,
            'name' => $documentEntity->name,
            'docNumber' => $documentEntity->docNumber,
            'docDate' => $documentEntity->docDate->toDateString(),
            'docExpireDate' => $documentEntity->docExpireDate->toDateString(),
            'visibility' => $documentEntity->visibility->value,
            'description' => $documentEntity->description,
        ]);
        $documentEntity->id = $record->id;
        return $documentEntity;
    }
    public function update(DocumentEntity $documentEntity): bool
    {
        return false;
    }
    public function destroy(int $categoryId): bool
    {
        return false;
    }
}
