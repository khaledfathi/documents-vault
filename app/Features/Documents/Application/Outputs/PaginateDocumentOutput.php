<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Outputs;

use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;

interface PaginateDocumentOutput
{
    /**
     * @param EntitiesWithPagination<DocumentEntity> $entitiesWithPagination
     */
    public function onSuccess(EntitiesWithPagination $entitiesWithPagination): void;
    public function onUnauthorized(): void;
    public function onFailure(string $error): void;
}
