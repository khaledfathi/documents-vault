<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\Document\FileEntity;
use App\Shared\Domain\Repositories\FileRepository;
use App\Shared\Infrastructure\Models\File;

final class EloquentFileRepository implements FileRepository
{
    public function store(FileEntity $fileEntity): FileEntity
    {
        $record = File::create([
            'document_id' => $fileEntity->documentId,
            'file' => $fileEntity->file,
        ]);
        $fileEntity->id = $record->id;
        return new FileEntity();
    }
    public function show(int $fileId): FileEntity
    {
        return new FileEntity();
    }
    public function update(FileEntity $fileEntity): bool
    {
        return false;
    }
    public function destroy(int $fileId): bool
    {
        return false;
    }
}
