<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Contracts;

use App\Features\Documents\Application\Outputs\ShowDocumentOutput;

interface ShowDocumentContract
{
    public function execute(int $documentId, ShowDocumentOutput $presenter): void;
}
