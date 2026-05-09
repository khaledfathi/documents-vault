<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Contracts;

use App\Features\Documents\Application\Outputs\ShowDocumentFileOutput;

interface ShowDocumentFileContract
{
    public function execute(int $documentId, int $fileId, ShowDocumentFileOutput $presenter): void;
}
