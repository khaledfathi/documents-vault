<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repositories;

use App\Shared\Domain\Entities\Document\FileEntity;

interface FileRepository
{
    public function store(FileEntity $fileEntity): FileEntity;
    public function show(int $fileId): ?FileEntity;
    public function showWithRelation(int $fileId): ?FileEntity;
    public function update(FileEntity $fileEntity): bool;
    public function destroy(int $fileId): bool;
}
