<?php
declare (strict_types=1);

namespace App\Shared\Domain\Entities\Document;

class CategoryEntity {
    /**
     * Summary of __construct
     * @param  int $id
     * @param  string $name
     * @param  string $description
     * @param  array<DocumentEntity> $documents
     */
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $description = null,
        public ?array $documents = null,
    ) { }
}