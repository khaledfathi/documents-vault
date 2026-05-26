<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Usecases;

use App\Features\Documents\Application\Contracts\PaginateDocumentContract;
use App\Features\Documents\Application\Outputs\PaginateDocumentOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\DocumentRepository;
use Exception;

final readonly class PaginateDocumentUsecase implements PaginateDocumentContract
{
    public function __construct(
        private PermissionGateway $permissionGateway,
        private CurrentUserContract $currentUser,
        private DocumentRepository $documentRepository,
    ) {}
    public function execute(PaginateDocumentOutput $presenter, int $perPage = 10): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_DOCUMENT)) {
                $presenter->onUnauthorized();
                return;
            }
            if ($this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_ALL_DOCUMENT)) {
                // paginate all document
                $record = $this->documentRepository->paginate($perPage);
            } else {
                // paginate all public documents
                $record = $this->documentRepository->paginatePublicDocumnetOnly( $perPage);
            };
            $presenter->onSuccess($record);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
