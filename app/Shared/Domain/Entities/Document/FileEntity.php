<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\Document;

class FileEntity
{
    public function __construct(
        public ?int $id = null,
        public ?int $documentId = null,
        public ?string $file = null,
    ) {}
}
