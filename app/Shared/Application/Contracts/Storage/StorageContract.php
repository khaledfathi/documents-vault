<?php

declare(strict_types=1);

namespace App\Shared\Application\Contracts\Storage;

use App\Shared\Application\Contracts\File;

interface Storage
{
    public function store(string $dir, File $file): string;
    public function remove(string $filePath): bool;
}
