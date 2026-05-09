<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Usecases;

use App\Features\Documents\Application\Contracts\ShowDocumentContract;
use App\Features\Documents\Application\Outputs\ShowDocumentOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\DocumentRepository;
use Exception;

final class ShowDocumentUsecase implements ShowDocumentContract
{
    public function __construct(
        private readonly DocumentRepository $documentrepository,
        private readonly PermissionGateway $permissionGateway,
        private readonly CurrentUserContract $currentUser,
    ) {}
    public function execute(int $documentId, ShowDocumentOutput $presenter): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_DOCUMENT)) {
                $presenter->onUnauthorized();
                return;
            }
            $documentEntity = $this->documentrepository->showWithRelation($documentId);
            if (! $documentEntity) {
                $presenter->onNotFound();
                return;
            }
            $presenter->onSuccess($documentEntity);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
