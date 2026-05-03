<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\Document;

use App\Shared\Domain\Contracts\DateProviderContract;
use App\Shared\Domain\Enums\Document\DocumentVisibilityType;
use App\Shared\Domain\Entities\Document\DocumentCategoryEntity;

final class DocumentEntity
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
     * @param ?array<FileEntity> $files
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
        public ?array $files = null,
    ) {}
    /**
     * @param $categoryIds array<int>
     * @return array<DocumentCategoryEntity>
     */
    public function createDocumentCategoryEntities(): array
    {
        return array_map(
            fn($categoryEntity) =>
            new DocumentCategoryEntity(
                documentId: $this->id,
                categoryId: $categoryEntity->id,
            ),
            $this->categories
        );
    }
    public function toArray()
    {
        $categories = [];
        if ($this->categories) {
            foreach ($this->categories as $category) {
                $categories[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                ];
            }
        }
        //
        $files = [];
        if ($this->files) {
            foreach ($this->files as $file) {
                $files[] = [
                    'id' => $file->id,
                    'file' => $file->file,
                    'documentId' => $file->documentId,
                ];
            }
        }
        //
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'docNumber' => $this->docNumber,
            'docDate' => $this->docDate->toDateString(),
            'docExpireDate' => $this->docExpireDate->toDateString(),
            'visibility' => $this->visibility,
            'description' => $this->description,
        ];
        if ($categories) $data['categories'] = $categories;
        if ($files) $data['files'] = $files;
        return $data;
    }
}
