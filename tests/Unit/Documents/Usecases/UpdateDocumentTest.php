<?php

declare(strict_types=1);

namespace Tests\Unit\Documents\Usecases;

use App\Features\Documents\Application\DTOs\UpdatedFileDTO;
use App\Features\Documents\Application\Outputs\UpdateDocumentOutput;
use App\Features\Documents\Application\Usecases\UpdateDocumentUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Application\Contracts\Storage\StorageContract;
use App\Shared\Application\Contracts\Storage\StorageDirContract;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Entities\Document\FileEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\DocumentRepository;
use App\Shared\Domain\Repositories\FileRepository;
use App\Shared\Domain\Repositories\UserRepository;
use App\Shared\Infrastructure\Storage\LaravelFile;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class UpdateDocumentTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    protected function setUp(): void
    {
        parent::setUp();
        $this->addToAssertionCount(1);
    }
    public function test_it_updates_document_record(): void
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $fileRepository = Mockery::mock(FileRepository::class);
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $storage = Mockery::mock(StorageContract::class);
        $storageDir = Mockery::mock(StorageDirContract::class);
        $presenter = Mockery::mock(UpdateDocumentOutput::class);

        $documentId = 1;
        $userId = 1;

        $documentEntity = new DocumentEntity(id: $documentId, userId: $userId);
        $fileEntity = new FileEntity(id: 1, documentId: $documentId, file: 'file_name', documentEntity: $documentEntity);
        $files = [$fileEntity];
        $documentEntity->files = $files;
        $updatedFileDTO = new UpdatedFileDTO(
            files: [new LaravelFile('', '', '', '', '')],
            deletedFileIds: [1],
        );

        $currentUser
            ->shouldReceive('id')
            ->andReturn($userId);

        $permissionGateway
            ->shouldReceive('can')
            ->with($userId, PermissionType::EDIT_DOCUMENT)
            ->andReturn(true);

        //----
        $permissionGateway
            ->shouldReceive('can')
            ->with($userId, PermissionType::EDIT_ANY_DOCUMENT)
            ->andReturn(false);

        $documentRepository
            ->shouldReceive('isOwnedByUser')
            ->with($documentId, $userId)
            ->andReturn(true);

        $userRepository
            ->shouldReceive('isRoot')
            ->with($userId)
            ->andReturn(false);

        $documentRepository
            ->shouldReceive('update')
            ->with($documentEntity)
            ->andReturn(true);

        $storageDir
            ->shouldReceive('documents')
            ->once()
            ->andReturn('document dir path');

        $fileRepository
            ->shouldReceive('showWithRelation')
            ->once()
            ->andReturn($fileEntity);

        $storage
            ->shouldReceive('remove')
            ->once()
            ->andReturn(true);

        $fileRepository
            ->shouldReceive('destroy')
            ->once()
            ->andReturn(true);

        $storage
            ->shouldReceive('store')
            ->once()
            ->andReturn('file path');

        $fileRepository
            ->shouldReceive('storeMany')
            ->once();

        $presenter
            ->shouldReceive('onSuccess')
            ->once();

        $usecase = new UpdateDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $userRepository,
            $documentRepository,
            $fileRepository,
            $storage,
            $storageDir
        );
        $usecase->execute($documentEntity, $updatedFileDTO, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_update_document_permission()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $fileRepository = Mockery::mock(FileRepository::class);
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $storage = Mockery::mock(StorageContract::class);
        $storageDir = Mockery::mock(StorageDirContract::class);
        $presenter = Mockery::mock(UpdateDocumentOutput::class);

        $documentId = 1;
        $userId = 1;

        $documentEntity = new DocumentEntity(id: $documentId, userId: $userId);
        $fileEntity = new FileEntity(id: 1, documentId: $documentId, file: 'file_name', documentEntity: $documentEntity);
        $files = [$fileEntity];
        $documentEntity->files = $files;
        $updatedFileDTO = new UpdatedFileDTO(
            files: [new LaravelFile('', '', '', '', '')],
            deletedFileIds: [1],
        );
        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::EDIT_DOCUMENT)
            ->andReturn(false);

        $permissionGateway
            ->shouldReceive('can')
            ->with($userId, PermissionType::EDIT_ANY_DOCUMENT)
            ->andReturn(false);

        $documentRepository
            ->shouldReceive('isOwnedByUser')
            ->with($documentId, $userId)
            ->andReturn(true);

        $userRepository
            ->shouldReceive('isRoot')
            ->with($userId)
            ->andReturn(false);

        $storageDir
            ->shouldReceive('documents')
            ->once()
            ->andReturn('document dir path');

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new UpdateDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $userRepository,
            $documentRepository,
            $fileRepository,
            $storage,
            $storageDir
        );
        $usecase->execute($documentEntity, $updatedFileDTO, $presenter);
    }
    public function test_it_fails_when_document_record_is_not_found()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $fileRepository = Mockery::mock(FileRepository::class);
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $storage = Mockery::mock(StorageContract::class);
        $storageDir = Mockery::mock(StorageDirContract::class);
        $presenter = Mockery::mock(UpdateDocumentOutput::class);

        $documentId = 1;
        $userId = 1;

        $documentEntity = new DocumentEntity(id: $documentId, userId: $userId);
        $fileEntity = new FileEntity(id: 1, documentId: $documentId, file: 'file_name', documentEntity: $documentEntity);
        $files = [$fileEntity];
        $documentEntity->files = $files;
        $updatedFileDTO = new UpdatedFileDTO(
            files: [new LaravelFile('', '', '', '', '')],
            deletedFileIds: [1],
        );

        $currentUser
            ->shouldReceive('id')
            ->andReturn($userId);

        $permissionGateway
            ->shouldReceive('can')
            ->with($userId, PermissionType::EDIT_DOCUMENT)
            ->andReturn(true);

        $permissionGateway
            ->shouldReceive('can')
            ->with($userId, PermissionType::EDIT_ANY_DOCUMENT)
            ->andReturn(false);

        $documentRepository
            ->shouldReceive('isOwnedByUser')
            ->with($documentId, $userId)
            ->andReturn(true);

        $userRepository
            ->shouldReceive('isRoot')
            ->with($userId)
            ->andReturn(false);

        $documentRepository
            ->shouldReceive('update')
            ->with($documentEntity)
            ->andReturn(false);

        $storageDir
            ->shouldReceive('documents')
            ->once()
            ->andReturn('document dir path');

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new UpdateDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $userRepository,
            $documentRepository,
            $fileRepository,
            $storage,
            $storageDir
        );
        $usecase->execute($documentEntity, $updatedFileDTO, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $fileRepository = Mockery::mock(FileRepository::class);
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $storage = Mockery::mock(StorageContract::class);
        $storageDir = Mockery::mock(StorageDirContract::class);
        $presenter = Mockery::mock(UpdateDocumentOutput::class);

        $documentId = 1;
        $userId = 1;

        $documentEntity = new DocumentEntity(id: $documentId, userId: $userId);
        $fileEntity = new FileEntity(id: 1, documentId: $documentId, file: 'file_name', documentEntity: $documentEntity);
        $files = [$fileEntity];
        $documentEntity->files = $files;
        $updatedFileDTO = new UpdatedFileDTO(
            files: [new LaravelFile('', '', '', '', '')],
            deletedFileIds: [1],
        );

        $currentUser
            ->shouldReceive('id')
            ->andReturn($userId);

        $permissionGateway
            ->shouldReceive('can')
            ->with($userId, PermissionType::EDIT_DOCUMENT)
            ->andReturn(true);

        $permissionGateway
            ->shouldReceive('can')
            ->with($userId, PermissionType::EDIT_ANY_DOCUMENT)
            ->andReturn(false);

        $documentRepository
            ->shouldReceive('isOwnedByUser')
            ->with($documentId, $userId)
            ->andReturn(true);

        $userRepository
            ->shouldReceive('isRoot')
            ->with($userId)
            ->andReturn(false);

        $documentRepository
            ->shouldReceive('update')
            ->with($documentEntity)
            ->andThrow(new Exception('database error'));

        $storageDir
            ->shouldReceive('documents')
            ->once()
            ->andReturn('document dir path');

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new UpdateDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $userRepository,
            $documentRepository,
            $fileRepository,
            $storage,
            $storageDir
        );
        $usecase->execute($documentEntity, $updatedFileDTO, $presenter);
    }
}
