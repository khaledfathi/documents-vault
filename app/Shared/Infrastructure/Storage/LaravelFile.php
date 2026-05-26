<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Storage;

use App\Shared\Application\Contracts\Storage\FileContract;

final readonly class LaravelFile implements FileContract
{
    public function __construct(
        private string $originalName,
        private string $originalExtension,
        private string $mimeType,
        private string $tempPath,
        private string $content,
    ) {}
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
