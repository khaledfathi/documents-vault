<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Storage;

use App\Shared\Application\Contracts\File;

final class LaravelFile implements File
{
    public function __construct(
        private readonly string $originalName,
        private readonly string $originalExtension,
        private readonly string $mimeType,
        private readonly string $tempPath,
        private readonly string $content,

    ) { }
    public function getOriginalName(): string
    {
        return $this->originalName;
    }
    public function getOriginalExtension(): string
    {
        return $this->originalExtension;
    }
    public function getMimeType(): string
    {
        return $this->mimeType;
    }
    public function getTempPath(): string
    {
        return $this->tempPath;
    }
    public function getContent(): string
    {
        return $this->content;
    }
}
