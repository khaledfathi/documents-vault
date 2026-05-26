<?php

declare(strict_types=1);

namespace Tests\Unit\Documents\Usecases;

use App\Features\Documents\Application\Outputs\StoreDocumentOutput;
use App\Features\Documents\Application\Usecases\StoreDocumentUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Application\Contracts\Storage\StorageContract;
use App\Shared\Application\Contracts\Storage\StorageDirContract;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Entities\Document\DocumentCategoryEntity;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Entities\Document\FileEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\DocumentCategoryRepository;
use App\Shared\Domain\Repositories\DocumentRepository;
use App\Shared\Domain\Repositories\FileRepository;
use App\Shared\Infrastructure\Storage\LaravelFile;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class StoreDocumentTest extends TestCase
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
    public function test_it_store_document_record(): void
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $documentCategoryRepository = Mockery::mock(DocumentCategoryRepository::class);
        $fileRepository = Mockery::mock(FileRepository::class);
        $storage = Mockery::mock(StorageContract::class);
        $storageDir = Mockery::mock(StorageDirContract::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(StoreDocumentOutput::class);

        $categoryEntity  = new CategoryEntity();
        $documentEntity = new DocumentEntity(id: 1, categories: [$categoryEntity]);
        $fileEntity = new FileEntity();
        $files = [new LaravelFile('', '', '', '', '',)];
        $documentCategoryEntities = [new DocumentCategoryEntity(), new DocumentCategoryEntity()];

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::CREATE_DOCUMENT)
            ->andReturn(true);

        $documentRepository
            ->shouldReceive('store')
            ->once()
            ->with($documentEntity)
            ->andReturn($documentEntity);

        $documentCategoryRepository
            ->shouldReceive('storeMany')
            ->once()
            ->andReturn($documentCategoryEntities);

        $documentCategoryRepository
            ->shouldReceive('getCategoriesByDocumentId')
            ->once()
            ->with(1)
            ->andReturn([$categoryEntity]);

        $storage
            ->shouldReceive('store')
            ->once()
            ->andReturn('stored file name');

        $storageDir
            ->shouldReceive('documents')
            ->once()
            ->with(1)
            ->andReturn('dir path');

        $fileRepository
            ->shouldReceive('store')
            ->once()
            ->andReturn($fileEntity);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($documentEntity);

        $usecase = new StoreDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $documentRepository,
            $documentCategoryRepository,
            $fileRepository,
            $storage,
            $storageDir,
        );
        $usecase->execute($documentEntity, $files, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_create_document_permission()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $documentCategoryRepository = Mockery::mock(DocumentCategoryRepository::class);
        $fileRepository = Mockery::mock(FileRepository::class);
        $storage = Mockery::mock(StorageContract::class);
        $storageDir = Mockery::mock(StorageDirContract::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(StoreDocumentOutput::class);

        $documentEntity = new DocumentEntity();
        $files = [];

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::CREATE_DOCUMENT)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new StoreDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $documentRepository,
            $documentCategoryRepository,
            $fileRepository,
            $storage,
            $storageDir,
        );
        $usecase->execute($documentEntity, $files, $presenter);
    }

    public function test_it_handles_unexpected_exception()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $documentCategoryRepository = Mockery::mock(DocumentCategoryRepository::class);
        $fileRepository = Mockery::mock(FileRepository::class);
        $storage = Mockery::mock(StorageContract::class);
        $storageDir = Mockery::mock(StorageDirContract::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(StoreDocumentOutput::class);

        $categoryEntity  = new CategoryEntity();
        $documentEntity = new DocumentEntity(id: 1, categories: [$categoryEntity]);
        $files = [];

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::CREATE_DOCUMENT)
            ->andReturn(true);

        $documentRepository
            ->shouldReceive('store')
            ->once()
            ->with($documentEntity)
            ->andReturn($documentEntity);

        $this->throwException(new Exception('exception happens after document record stored'));

        $documentRepository
            ->shouldReceive('destroy')
            ->once()
            ->with(1)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once();

        $usecase = new StoreDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $documentRepository,
            $documentCategoryRepository,
            $fileRepository,
            $storage,
            $storageDir,
        );
        $usecase->execute($documentEntity, $files, $presenter);
    }
}
