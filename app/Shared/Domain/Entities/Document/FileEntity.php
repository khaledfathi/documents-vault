<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\Document;

final class FileEntity
{
    public function __construct(
        public ?int $id = null,
        public ?int $documentId = null,
        public ?string $file = null,
        public ?DocumentEntity $documentEntity = null,
    ) {}

    /**
     * @return array{
     * id: int,
     * name: string,
     * email: string.
     * phones: array<array{ id: int, phone: string}>|null,
     * group: array {id:int , name:string}|null,
     * permissions: array{id: int , permission: string}|null
     * }
     * */
    public function toArray():array {
        return [
            'id' => $this->id,
            'documentId' => $this->documentId,
            'file' => $this->file,
            'documentEntity' => $this->documentEntity->toArray(),
        ];
    }
}
