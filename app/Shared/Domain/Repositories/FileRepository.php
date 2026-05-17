<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repositories;

use App\Shared\Domain\Entities\Document\FileEntity;

interface FileRepository
{
    public function store(FileEntity $fileEntity): FileEntity;
    /**
     * @param array<FileEntity> $fileEntities
     * @return array<int> list of records Id
     */
    public function storeMany(array $fileEntities): bool;
    public function show(int $fileId): ?FileEntity;
    public function showWithRelation(int $fileId): ?FileEntity;
    public function update(FileEntity $fileEntity): bool;
    public function destroy(int $fileId): bool;
    /**
     * @param array<int> $fileIds
     * @return int count of effected rows
     */
    public function destroyMany(array $fileIds): int;
}
