<?php
declare (strict_types=1);
namespace App\Shared\Domain\Entities\Document; 

class DocumentCategoryEntity {
    public function __construct(
        public ?int $id = null,
        public ?int $documentId = null ,
        public ?int $categoryId = null,
        public ?CategoryEntity  $category = null,
        public ?DocumentEntity $document = null ,
    ) { }
}


