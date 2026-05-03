<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Storage;

use App\Shared\Application\Contracts\Storage\FileContract;
use App\Shared\Application\Contracts\Storage\StorageContract;
use Illuminate\Support\Facades\Storage as FacadesStorage;

final class LaravelStorage implements StorageContract
{
    public function store(string $dir, filecontract $file): string
    {
        $fileName = bin2hex(random_bytes(16)) . random_int(100, 999) . '.' . $file->getoriginalextension();
        $path = $dir . $fileName;
        facadesstorage::put($path, $file->getcontent());
        return $fileName;
    }
    public function remove(string $filePath): bool
    {
        return FacadesStorage::delete($filePath);
    }
    public function removeDirectory (string $dir): bool{
        return FacadesStorage::deleteDirectory($dir);
    }
}
