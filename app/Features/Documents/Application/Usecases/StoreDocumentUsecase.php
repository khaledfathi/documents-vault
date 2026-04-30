<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Usecases;

use App\Features\Documents\Application\Contracts\StoreDocumentContract;
use App\Features\Documents\Application\Outputs\StoreDocumentOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Application\Contracts\Storage\FileContract;
use App\Shared\Application\Contracts\Storage\StorageContract;
use App\Shared\Application\Contracts\Storage\StorageDirContract;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Entities\Document\FileEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\DocumentRepository;
use App\Shared\Domain\Repositories\FileRepository;
use Exception;

final class StoreDocumentUsecase implements StoreDocumentContract
{
    public function __construct(
        private readonly PermissionGateway $permissionGateway,
        private readonly CurrentUserContract $currentUser,
        private readonly DocumentRepository $documentRepository,
        private readonly FileRepository $fileRepository,
        private readonly StorageContract $storage,
        private readonly StorageDirContract $storageDir,
    ) {}
    public function execute(DocumentEntity $documentEntity, array $files ,  StoreDocumentOutput $presenter): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::CREATE_DOCUMENT)) {
                $presenter->onUnauthorized();
                return;
            }
            // ----------------------
            //1- store document
            $documentEntity = $this->documentRepository->store($documentEntity);
            //2- store files releated to this document ( in database and private place);
            $fileEntities = $this->storeFiles($files , $documentEntity->id);
            //3-  add files eneities to document entitiy ;
            $documentEntity->files = $fileEntities;
            $presenter->onSuccess($documentEntity);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
    /**
    * @param $files array<FileContract>
    */
    private function storeFiles (array $files , $documentId):array{
        $dir = $this->storageDir->documents($documentId);
        $entities = [];
        foreach($files as $file){
            //copy file to private storage
            $fileName = $this->storage->store($dir , $file);
            //store file in database
            $fileEntitiy = $this->fileRepository->store(
                new FileEntity(
                    documentId : $documentId,
                    file : $fileName,
                )
            );
            $entities[] = $fileEntitiy ;
        }
        return $entities;
    }
}
