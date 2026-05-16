<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Usecases;

use App\Features\Documents\Application\Contracts\UpdateDocumentContract;
use App\Features\Documents\Application\DTOs\UpdatedFileDTO;
use App\Features\Documents\Application\Outputs\UpdateDocumentOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Application\Contracts\Storage\StorageContract;
use App\Shared\Application\Contracts\Storage\StorageDirContract;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Entities\Document\FileEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\DocumentRepository;
use App\Shared\Domain\Repositories\FileRepository;
use Exception;

final class UpdateDocumentUsecase implements UpdateDocumentContract
{
    private string $dir = "";
    public function __construct(
        private readonly PermissionGateway $permissionGateway,
        private readonly CurrentUserContract $currentUser,
        private readonly DocumentRepository $documentRepository,
        private readonly FileRepository $fileRepository,
        private readonly StorageContract $storage,
        private readonly StorageDirContract $storageDir,
    ) {}
    public function execute(DocumentEntity $documentEntity, ?UpdatedFileDTO $updatedFilesDTO, UpdateDocumentOutput $presenter): void
    {
        $this->dir = $this->storageDir->documents($documentEntity->id);
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::EDIT_DOCUMENT)) {
                $presenter->onUnauthorized();
                return;
            }
            //handle update document
            $status = $this->documentRepository->update($documentEntity);
            if (! $status) {
                $presenter->onNotFound();
                return;
            }
            if ($updatedFilesDTO) $this->updateFiles($updatedFilesDTO, $documentEntity->id);
            $presenter->onSuccess();
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
    private function updateFiles(UpdatedFileDTO $updatedFileDTO, int $documentId)
    {
        if (!$updatedFileDTO) return;
        //delete selected files by ids
        if ($updatedFileDTO->deletedFileIds) {
            foreach ($updatedFileDTO->deletedFileIds as $fileId) {
                $fileRecord = $this->fileRepository->showWithRelation((int)$fileId);
                //remove file if exists
                if ($fileRecord) {
                    //ensure the file is releated to the current document , and belong the current user
                    if (
                        $fileRecord->documentEntity->userId != $this->currentUser->id() ||
                        $fileRecord->documentEntity->id != $documentId
                    ) continue;
                    $this->storage->remove($this->dir . $fileRecord->file);
                    $this->fileRepository->destroy((int)$fileId);
                }
            }
        }
        //store new files
        if ($updatedFileDTO->files) {
            $fileEntities = [];
            foreach ($updatedFileDTO->files as $file) {
                //store files content
                $fileName = $this->storage->store($this->dir, $file);
                $fileEntities[] = new FileEntity(
                    documentId: $documentId,
                    file: $fileName,
                );
            }
            //store in DB
            $this->fileRepository->storeMany($fileEntities);
        }
    }
}
