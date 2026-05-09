<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Entities\Document\FileEntity;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Enums\Document\DocumentVisibilityType;
use App\Shared\Domain\Repositories\DocumentRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Infrastructure\Models\Document;
use App\Shared\Infrastructure\Utilities\CarbonDateUtility;

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
            'doc_number' => $documentEntity->docNumber,
            'doc_date' => $documentEntity->docDate->toDateString(),
            'doc_expire_date' => $documentEntity->docExpireDate->toDateString(),
            'visibility' => $documentEntity->visibility->value,
            'description' => $documentEntity->description,
        ]);
        $documentEntity->id = $record->id;
        return $documentEntity;
    }
    public function showWithRelation(int $documentId): ?DocumentEntity
    {
        $record = Document::with('documentCategories', 'user',  'files', 'documentCategories.category')->where('id', $documentId)->first();
        if (! $record) return null;
        //user
        $userRecord = $record->user;
        $userEntity = new UserEntity(
            id: $userRecord->id,
            groupId: $userRecord->group_id,
            name: $userRecord->name,
            email: $userRecord->email,
        );
        //categories
        $categoryEntities = [];
        $documentCategoryRecords = $record->documentCategories;
        foreach ($documentCategoryRecords as $documentCategory) {
            $categoryEntities[] = new CategoryEntity(
                id: $documentCategory->category->id,
                name: $documentCategory->category->name,
                description: $documentCategory->category->description,
            );
        }
        //files
        $fileEntities = [];
        $fileRecords = $record->files;
        foreach ($fileRecords as $file) {
            $fileEntities[] = new FileEntity(
                id: $file->id,
                documentId: $file->document_id,
                file: $file->file,
            );
        }
        //document
        $documentEntity = new DocumentEntity(
            id: $record->id,
            userId: $record->user_id,
            name: $record->name,
            docNumber: $record->doc_number,
            docDate: CarbonDateUtility::from($record->doc_date),
            docExpireDate: CarbonDateUtility::from($record->doc_expire_date),
            visibility: DocumentVisibilityType::from($record->visibility),
            description: $record->description,
            userEntity: $userEntity,
            categories: $categoryEntities,
            files: $fileEntities,
        );
        //
        return $documentEntity;
    }
    public function update(DocumentEntity $documentEntity): bool
    {
        return false;
    }
    public function destroy(int $documentId): bool
    {
        $record = Document::find($documentId);
        if (! $record) return false;
        $record->delete();
        return true;
    }
}
