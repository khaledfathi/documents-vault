<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repositories;

use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;

interface DocumentRepository
{
    /**
     * @return EntitiesWithPagination<DocumentEntity>
     */
    public function paginate(int $perPage = 10): EntitiesWithPagination;
    /**
     * @return EntitiesWithPagination<DocumentEntity>
     */
    public function paginatePublicDocumnetOnly( $perPage):EntitiesWithPagination;
    /**
     * @return EntitiesWithPagination<DocumentEntity>
     */
    public function paginateRelatedToUser(int $userId, int $perPage = 10): EntitiesWithPagination;
    public function show(int $documentId): ?DocumentEntity;
    public function showWithRelation(int $documentId): ?DocumentEntity;
    /**
    * show record with relations where column 'visibility' is 'public'
    * @param int $documentId
    * @return ?DocumentEntity
    */
    public function showWithRelationPublicOnly(int $documentId): ?DocumentEntity;
    public function store(DocumentEntity $documentEntity): DocumentEntity;
    public function update(DocumentEntity $documentEntity): bool;
    public function destroy(int $documentId): bool;
    public function isOwnedByUser ($documentId , int $userId):bool;
}
