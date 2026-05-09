<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Contracts;

use App\Features\Documents\Application\Outputs\DestroyDocumentOutput;

interface DestroyDocumentContract
{
    public function execute(int $documentId, DestroyDocumentOutput $presenter): void;
}
