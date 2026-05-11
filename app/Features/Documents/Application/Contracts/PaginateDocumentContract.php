<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Contracts;

use App\Features\Documents\Application\Outputs\PaginateDocumentOutput;

interface PaginateDocumentContract
{
    public function execute(PaginateDocumentOutput $presenter, int $perPage = 10): void;
}
