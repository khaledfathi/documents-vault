<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Usecases;

use App\Features\Documents\Application\Contracts\StoreDocumentContract;
use App\Features\Documents\Application\Outputs\StoreDocumentOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Application\Contracts\Storage\StorageContract;
use App\Shared\Application\Contracts\Storage\StorageDirContract;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Entities\Document\FileEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\DocumentCategoryRepository;
use App\Shared\Domain\Repositories\DocumentRepository;
use App\Shared\Domain\Repositories\FileRepository;
use Exception;

final class StoreDocumentUsecase implements StoreDocumentContract
{
    public function __construct(
        private readonly PermissionGateway $permissionGateway,
        private readonly CurrentUserContract $currentUser,
        private readonly DocumentRepository $documentRepository,
        private readonly DocumentCategoryRepository $documentCategoryRepository,
        private readonly FileRepository $fileRepository,
        private readonly StorageContract $storage,
        private readonly StorageDirContract $storageDir,
    ) {}
    public function execute(DocumentEntity $documentEntity, array $files,  StoreDocumentOutput $presenter): void
    {
        $record = null;
        $fileEntities = null;
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::CREATE_DOCUMENT)) {
                $presenter->onUnauthorized();
                return;
            }
            // store document data (Record)
            $record = $this->documentRepository->store($documentEntity);
            // add document to categories (Record)
            $documentCategoryEntities = $record->createDocumentCategoryEntities();
            $this->documentCategoryRepository->storeMany($documentCategoryEntities);
            //get categories details
            $categoryEntities = $this->documentCategoryRepository->getCategoriesByDocumentId($record->id);
            // store document files
            $fileEntities = $this->storeFiles($files, $record->id);
            // add files and categories to document eneities
            $documentEntity->files = $fileEntities;
            $documentEntity->categories = $categoryEntities;
            // presenter
            $presenter->onSuccess($record);
        } catch (Exception $e) {
            if ($record) {
                $this->documentRepository->destroy($record->id);
            }
            $presenter->onFailure($e->getMessage());
        }
    }
    /**
     * @param $files array<FileEntity>
     * @return array<FileEntity>
     */
    private function storeFiles(array $files, int $documentId): array
    {
        $entities = [];
        foreach ($files as $file) {
            //copy file to private storage
            $fileName = $this->storage->store($this->storageDir->documents($documentId), $file);
            //store file in database
            $entities[] = $this->fileRepository->store(
                new FileEntity(
                    documentId: $documentId,
                    file: $fileName,
                )
            );
        }
        return $entities;
    }
    /**
     * @param $files array<FileContract>
     */
    private function removeFiles(int $documentId): void
    {
        $this->storage->removeDirectory($this->storageDir->documents($documentId));
    }
}
