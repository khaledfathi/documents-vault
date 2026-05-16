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
use App\Shared\Infrastructure\Repositories\Eloquent\Traits\PaginatorTrait;
use App\Shared\Infrastructure\Utilities\CarbonDateUtility;

final class EloquentDocumentRepository implements DocumentRepository
{
    use PaginatorTrait;

    public function paginate(int $perPage = 10): EntitiesWithPagination
    {
        $records = Document::with('documentCategories', 'user',  'files', 'documentCategories.category')->paginate($perPage);
        //entities
        $documentEntities = [];
        foreach ($records as $record) {
            //user
            $userRecord = $record->user;
            $userEntity = new UserEntity(
                id: $userRecord->id,
                groupId: $userRecord->group_id,
                name: $userRecord->name,
                email: $userRecord->email,
                isRoot: EloquentUserRepository::isRoot($record->id),
            );
            //categories
            $categories = [];
            $documentCategoryRecords = $record->documentCategories;
            foreach ($documentCategoryRecords as $documentCategory) {
                $categories[] = new CategoryEntity(
                    id: $documentCategory->category->id,
                    name: $documentCategory->category->name,
                    description: $documentCategory->category->description,
                );
            }
            //files
            $files = [];
            $fileRecords = $record->files;
            foreach ($fileRecords as $file) {
                $files[] = new FileEntity(
                    id: $file->id,
                    documentId: $file->document_id,
                    file: $file->file,
                );
            }
            //documentEntity
            $documentEntities[] = new DocumentEntity(
                id: $record->id,
                userId: $record->user_id,
                name: $record->name,
                docNumber: $record->doc_number,
                docDate: $record->doc_date ? CarbonDateUtility::from($record->doc_date) : null,
                docExpireDate: $record->doc_expire_date ? CarbonDateUtility::from($record->doc_expire_date) : null,
                visibility: DocumentVisibilityType::from($record->visibility),
                description: $record->description,
                categories: $categories,
                files: $files,
                userEntity: $userEntity,
            );
        }
        //pagination
        $pagination = $this->mapPaginator($records, $perPage);
        return new EntitiesWithPagination($pagination, $documentEntities);
    }
    public function paginateRelatedToUser(int $userId, int $perPage = 10): EntitiesWithPagination
    {
        $records = Document::with('documentCategories', 'user',  'files', 'documentCategories.category')
            ->where('user_id', $userId)->paginate($perPage);
        //entities
        $documentEntities = [];
        foreach ($records as $record) {
            //user
            $userRecord = $record->user;
            $userEntity = new UserEntity(
                id: $userRecord->id,
                groupId: $userRecord->group_id,
                name: $userRecord->name,
                email: $userRecord->email,
                isRoot: EloquentUserRepository::isRoot($record->id),
            );
            //categories
            $categories = [];
            $documentCategoryRecords = $record->documentCategories;
            foreach ($documentCategoryRecords as $documentCategory) {
                $categories[] = new CategoryEntity(
                    id: $documentCategory->category->id,
                    name: $documentCategory->category->name,
                    description: $documentCategory->category->description,
                );
            }
            //files
            $files = [];
            $fileRecords = $record->files;
            foreach ($fileRecords as $file) {
                $files[] = new FileEntity(
                    id: $file->id,
                    documentId: $file->document_id,
                    file: $file->file,
                );
            }
            //documentEntity
            $documentEntities[] = new DocumentEntity(
                id: $record->id,
                userId: $record->user_id,
                name: $record->name,
                docNumber: $record->doc_number,
                docDate: $record->doc_date ? CarbonDateUtility::from($record->doc_date) : null,
                docExpireDate: $record->doc_expire_date ? CarbonDateUtility::from($record->doc_expire_date) : null,
                visibility: DocumentVisibilityType::from($record->visibility),
                description: $record->description,
                categories: $categories,
                files: $files,
                userEntity: $userEntity,
            );
        }
        //pagination
        $pagination = $this->mapPaginator($records, $perPage);
        return new EntitiesWithPagination($pagination, $documentEntities);
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
        $record = Document::find($documentEntity->id);
        if ($record){
            $record->update([
                'name'=>$documentEntity->name,
                'doc_number'=>$documentEntity->docNumber,
                'doc_date'=>$documentEntity->docDate->toDateString(),
                'doc_expire_date'=>$documentEntity->docExpireDate->toDateString(),
                'visibility'=>$documentEntity->visibility->value,
                'description'=>$documentEntity->description,
            ]);
            return true;
        }
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
