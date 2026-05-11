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

final class PaginateDocumentUsecase implements PaginateDocumentContract
{
    public function __construct(
        private readonly PermissionGateway $permissionGateway,
        private readonly CurrentUserContract $currentUser,
        private readonly DocumentRepository $documentRepository,
    ) {}
    public function execute(PaginateDocumentOutput $presenter, int $perPage = 10): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_DOCUMENT)) {
                $presenter->onUnauthorized();
                return;
            }
            //for user in admin group or root user
            if ($this->currentUser->entity()->isRoot || $this->currentUser->entity()->group->isAdmin) {
                $record = $this->documentRepository->paginate($perPage);
                // paginate all document
            } else {
                $record = $this->documentRepository->paginateRelatedToUser($this->currentUser->id(), $perPage);
                // paginate document releated to current user
            };
            $presenter->onSuccess($record);
            //show only record releated to the current use , or all if admin user
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
