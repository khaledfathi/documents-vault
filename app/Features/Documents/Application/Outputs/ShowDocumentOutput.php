<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Outputs;

use App\Shared\Domain\Entities\Document\DocumentEntity;

interface ShowDocumentOutput
{
    public function onSuccess(DocumentEntity $documentEntity): void;
    public function onNotFound(): void;
    public function onUnauthorized(): void;
    public function onFailure(string $error): void;
}
