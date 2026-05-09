<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Outputs;

use App\Shared\Domain\Entities\Document\DocumentEntity;

interface StoreDocumentOutput
{
    public function onSuccess(DocumentEntity $documentEntity): void;
    public function onUnauthorized(): void;
    public function onFailure(string $error): void;
}
