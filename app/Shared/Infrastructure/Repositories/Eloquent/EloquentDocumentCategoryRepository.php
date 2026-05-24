<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Entities\Document\DocumentCategoryEntity;
use App\Shared\Domain\Repositories\DocumentCategoryRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Infrastructure\Models\DocumentCategory;

final class EloquentDocumentCategoryRepository implements DocumentCategoryRepository
{
    public function paginate(int $perPage = 10): EntitiesWithPagination
    {
        return new EntitiesWithPagination();
    }
    public function show(int $documentCategoryId): ?DocumentCategoryEntity
    {
        return new DocumentCategoryEntity();
    }
    /**
     * @param int $documentId
     * @return array<DocumentCategoryEntity>
     */
    public function getCategoriesByDocumentId(int $documentId): array
    {
        $records = DocumentCategory::with('category')->where('document_id', $documentId)->get();
        $documentCategoryEntities = [];
        foreach ($records as $record) {
            $documentCategoryEntities[] = new CategoryEntity(
                id: $record->category->id,
                name: $record->category->name,
                description: $record->category->description,
            );
        }
        return $documentCategoryEntities;
    }
    public function store(DocumentCategoryEntity $documentCategoryEntity): DocumentCategoryEntity
    {
        $record = DocumentCategory::create([
            'document_id' => $documentCategoryEntity->documentId,
            'category_id' => $documentCategoryEntity->categoryId,
        ]);
        $documentCategoryEntity->id = $record->id;
        return new DocumentCategoryEntity();
    }
    public function storeMany(array $documentCategoryEntities): array
    {
        return array_map(function ($documentCategoryEntity) {
            $record = DocumentCategory::create([
                'document_id' => $documentCategoryEntity->documentId,
                'category_id' => $documentCategoryEntity->categoryId,
            ]);
            $documentCategoryEntity->id = $record->id;
            $documentCategoryEntity->documentId = $record->document_id;
            $documentCategoryEntity->categoryId = $record->category_id;
            return $documentCategoryEntity;
        }, $documentCategoryEntities);
    }
    public function update(DocumentCategoryEntity $documentCategoryEntity): bool
    {
        return false;
    }
    public function destroy(int $documentCategoryId): bool
    {
        return false;
    }
}
