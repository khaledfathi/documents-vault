<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Storage;

use App\Shared\Application\Contracts\Storage\FileContract;
use App\Shared\Application\Contracts\Storage\StorageContract;
use Illuminate\Support\Facades\Storage as FacadesStorage;

final class LaravelStorage implements StorageContract
{
    public function store(string $dir, FileContract $file): string
    {
        $fileName = bin2hex(random_bytes(16)) . random_int(100, 999) . '.' . $file->getOriginalExtension();
        $path = $dir . $fileName;
        FacadesStorage::put($path, $file->getContent());
        return $fileName;
    }
    public function remove(string $filePath): bool
    {
        return FacadesStorage::delete($filePath);
    }
}
