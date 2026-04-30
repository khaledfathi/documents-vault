<?php

declare(strict_types=1);

namespace App\Shared\Application\Contracts\Storage;


interface StorageContract
{
    public function store(string $dir, FileContract $file): string;
    public function remove(string $filePath): bool;
}
