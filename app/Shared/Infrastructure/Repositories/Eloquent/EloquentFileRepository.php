<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Entities\Document\FileEntity;
use App\Shared\Domain\Enums\Document\DocumentVisibilityType;
use App\Shared\Domain\Repositories\FileRepository;
use App\Shared\Infrastructure\Models\File;
use App\Shared\Infrastructure\Utilities\CarbonDateUtility;

final class EloquentFileRepository implements FileRepository
{
    public function store(FileEntity $fileEntity): FileEntity
    {
        $record = File::create([
            'document_id' => $fileEntity->documentId,
            'file' => $fileEntity->file,
        ]);
        $fileEntity->id = $record->id;
        return $fileEntity;
    }
    public function show(int $fileId): ?FileEntity
    {
        return new FileEntity();
    }
    public function showWithRelation(int $fileId): ?FileEntity
    {
        $record = File::with('document')->find($fileId);
        if (! $record) return null;
        //user entity
        $documentRecord = $record->document;
        $documentEntity = new DocumentEntity(
            id: $documentRecord->id,
            userId: $documentRecord->user_id,
            name: $documentRecord->name,
            docNumber: $documentRecord->doc_number,
            docDate: CarbonDateUtility::from($documentRecord->doc_date),
            docExpireDate: $documentRecord->doc_expire_date ? CarbonDateUtility::from($documentRecord->doc_expire_date) : null,
            visibility: DocumentVisibilityType::from($documentRecord->visibility),
            description: $documentRecord->description,
        );
        //file entity
        $fileEntity = new FileEntity(
            id: $record->id,
            documentId: $record->document_id,
            file: $record->file,
            documentEntity: $documentEntity,
        );
        return $fileEntity;
    }
    public function update(FileEntity $fileEntity): bool
    {
        return false;
    }
    public function destroy(int $fileId): bool
    {
        $record = File::find($fileId);
        if (!$record) return false;
        $record->delete();
        return true;
    }
}
