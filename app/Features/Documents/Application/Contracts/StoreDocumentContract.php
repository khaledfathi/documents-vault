<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Contracts;

use App\Features\Documents\Application\Outputs\StoreDocumentOutput;
use App\Shared\Application\Contracts\Storage\FileContract;
use App\Shared\Domain\Entities\Document\DocumentEntity;

interface StoreDocumentContract
{
    /**
     * @param $files array<FileContract>
     */
    public function execute(DocumentEntity $documentEntity, array $files,  StoreDocumentOutput $presenter): void;
}
