<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Usecases;

use App\Features\Documents\Application\Contracts\DestroyDocumentContract;
use App\Features\Documents\Application\Outputs\DestroyDocumentOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Application\Contracts\Storage\StorageContract;
use App\Shared\Application\Contracts\Storage\StorageDirContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\DocumentRepository;
use Exception;

final readonly class DestroyDocumentUsecase implements DestroyDocumentContract
{
    public function __construct(
        private PermissionGateway $permissionGateWay,
        private CurrentUserContract $currentUser,
        private DocumentRepository $documentRepository,
        private StorageDirContract $storageDir,
        private StorageContract $storage,
    ) {}
    public function execute(int $documentId, DestroyDocumentOutput $presenter): void
    {
        try {
            if (! $this->permissionGateWay->can($this->currentUser->id(), PermissionType::DELETE_DOCUMENT)) {
                $presenter->onUnauthorized();
                return;
            }
            $status = $this->documentRepository->destroy($documentId);
            if ($status) {
                //remove files with directory releated to this removed document.
                $this->storage->removeDirectory($this->storageDir->documents($documentId));
                $presenter->onSuccess();
            } else {
                $presenter->onNotFound();
            }
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
