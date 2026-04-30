<?php

declare(strict_types=1);

namespace App\Shared\Application\Contracts\Storage;

interface FileContract
{
    public function getOriginalName(): string;
    public function getOriginalExtension(): string;
    public function getMimeType(): string;
    public function getTempPath(): string;
    public function getContent(): string;
}
