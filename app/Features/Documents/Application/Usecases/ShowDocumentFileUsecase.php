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
    public function execute( int $fileId, ShowDocumentFileOutput $presenter): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_DOCUMENT)) {
                $presenter->onUnauthorized();
                return;
            }
            if ($this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_ALL_DOCUMENT)) {

                $fileEntity = $this->fileRepository->showWithRelation($fileId);
            } else {
                $fileEntity = $this->fileRepository->showWithRelationPublicOnly($fileId);
            }
            if (! $fileEntity) {
                $presenter->onNotFound();
                return;
            }
            $presenter->onSuccess($fileEntity);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
