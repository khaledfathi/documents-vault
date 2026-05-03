<?php

declare(strict_types=1);

namespace App\Shared\Application\Utilities;

use App\Shared\Application\Contracts\Storage\StorageDirContract;

final class UtilityStorageDir implements StorageDirContract
{
    private string $prefix = '';

    public function privatePath(): StorageDirContract
    {
        $this->prefix = storage_path('app/private/');
        return $this;
    }
    public function publicPath(): StorageDirContract
    {
        $this->prefix = storage_path('app/public/');
        return $this;
    }
    public function documents(int $documentId): string
    {
        return  $this->prefix . "documents/$documentId/";
    }
}
