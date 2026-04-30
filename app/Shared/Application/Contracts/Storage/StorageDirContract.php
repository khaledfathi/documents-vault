<?php

declare(strict_types=1);

namespace App\Shared\Application\Contracts\Storage;

interface StorageDirContract
{
    public function privatePath(): StorageDirContract;
    public function publicPath(): StorageDirContract;
    public function documents(int $documentId): string;
}
