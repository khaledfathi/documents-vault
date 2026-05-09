<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Usecases;

use App\Features\Documents\Application\Contracts\ShowDocumentFileContract;
use App\Features\Documents\Application\Outputs\ShowDocumentFileOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\FileRepository;
use Exception;

final class ShowDocumentFileUsecase implements ShowDocumentFileContract
{
    public function __construct(
        private readonly FileRepository $fileRepository,
        private readonly PermissionGateway $permissionGateway,
        private readonly CurrentUserContract $currentUser,
    ) {}
    public function execute(int $documentId, int $fileId, ShowDocumentFileOutput $presenter): void
    {
        try {

            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_DOCUMENT)) {
                $presenter->onUnauthorized();
                return;
            }
            $fileEntity = $this->fileRepository->showWithRelation($fileId);
            if (! $fileEntity) {
                $presenter->onNotFound();
                return;
            }
            //check : is current document owned by current user
            if (! $fileEntity->documentEntity->userId == $this->currentUser) {
                $presenter->onForbidden();
                return;
            }
            $presenter->onSuccess($fileEntity);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
