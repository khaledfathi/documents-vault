<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Contracts;

use App\Features\Documents\Application\DTOs\UpdatedFileDTO;
use App\Features\Documents\Application\Outputs\UpdateDocumentOutput;
use App\Shared\Domain\Entities\Document\DocumentEntity;

interface UpdateDocumentContract
{
    public function execute(DocumentEntity $documentEntity, ?UpdatedFileDTO $updatedFilesDTO, UpdateDocumentOutput $presenter): void;
}
