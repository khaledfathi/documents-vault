<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\Document;

use App\Shared\Domain\Contracts\DateProviderContract;
use App\Shared\Domain\Enums\Document\DocumentVisibilityType;

class DocumentEntity
{
    /**
     * Summary of __construct
     * @param ?int $id
     * @param ?int $userId
     * @param ?string $name
     * @param ?string $docNumber
     * @param ?DateProviderContract $docDate
     * @param ?DateProviderContract $docExpireDate
     * @param ?DocumentVisibilityType $visibility
     * @param ?int $description
     * @param ?array<CategoryEntity> $categories
     */
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?string $name = null,
        public ?string $docNumber = null,
        public ?DateProviderContract $docDate = null,
        public ?DateProviderContract $docExpireDate = null,
        public ?DocumentVisibilityType $visibility = null,
        public ?string $description = null,
        public ?array $categories = null,
        public ?FileEntity $file = null,
    ) {}
}
