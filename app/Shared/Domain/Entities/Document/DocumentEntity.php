<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\Document;

use App\Shared\Domain\Contracts\DateProviderContract;
use App\Shared\Domain\Enums\Document\DocumentVisibilityType;
use App\Shared\Domain\Entities\Document\DocumentCategoryEntity;
use App\Shared\Domain\Entities\User\UserEntity;

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
        public ?UserEntity $userEntity = null,
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
                $categories[] = $category->toArray();
            }
        }
        //
        $files = [];
        if ($this->files) {
            foreach ($this->files as $file) {
                $files[] = $file->toArray();
            }
        }
        //
        $data = [
            'id' => $this->id,
            'userId' => $this->userId,
            'name' => $this->name,
            'docNumber' => $this->docNumber,
            'docDate' => $this->docDate?->toDateString(),
            'docExpireDate' => $this->docExpireDate?->toDateString(),
            'visibility' => $this->visibility,
            'description' => $this->description,
            'categories' => $categories,
            'files' => $files,
        ];

        if ($this->userEntity) $data['user'] = array_diff_key($this->userEntity->toArray(), array_flip(['phones', 'group']));
        return $data;
    }
}
