<?php

declare(strict_types=1);

namespace Tests\Unit\Documents\Usecases;

use App\Features\Documents\Application\Outputs\DestroyDocumentOutput;
use App\Features\Documents\Application\Usecases\DestroyDocumentUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Application\Contracts\Storage\StorageContract;
use App\Shared\Application\Contracts\Storage\StorageDirContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\DocumentRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class DestroyDocumentTest extends TestCase
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
    public function test_it_destroy_document_record(): void
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $storage = Mockery::mock(StorageContract::class);
        $storageDir = Mockery::mock(StorageDirContract::class);
        $presenter = Mockery::mock(DestroyDocumentOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::DELETE_DOCUMENT)
            ->andReturn(true);

        $documentRepository
            ->shouldReceive('destroy')
            ->once()
            ->with(1)
            ->andReturn(true);

        $storageDir
            ->shouldReceive('documents')
            ->once()
            ->andReturn('dir path');

        $storage
            ->shouldReceive('removeDirectory')
            ->once();

        $presenter
            ->shouldReceive('onSuccess')
            ->once();

        $usecase = new DestroyDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $documentRepository,
            $storageDir,
            $storage
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_destroy_document_permission()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $storage = Mockery::mock(StorageContract::class);
        $storageDir = Mockery::mock(StorageDirContract::class);
        $presenter = Mockery::mock(DestroyDocumentOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::DELETE_DOCUMENT)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new DestroyDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $documentRepository,
            $storageDir,
            $storage
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_document_record_is_not_found()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $storage = Mockery::mock(StorageContract::class);
        $storageDir = Mockery::mock(StorageDirContract::class);
        $presenter = Mockery::mock(DestroyDocumentOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::DELETE_DOCUMENT)
            ->andReturn(true);

        $documentRepository
            ->shouldReceive('destroy')
            ->once()
            ->with(1)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new DestroyDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $documentRepository,
            $storageDir,
            $storage
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $storage = Mockery::mock(StorageContract::class);
        $storageDir = Mockery::mock(StorageDirContract::class);
        $presenter = Mockery::mock(DestroyDocumentOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::DELETE_DOCUMENT)
            ->andReturn(true);

        $documentRepository
            ->shouldReceive('destroy')
            ->once()
            ->with(1)
            ->andThrow(new Exception('database error'));

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new DestroyDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $documentRepository,
            $storageDir,
            $storage
        );
        $usecase->execute(1, $presenter);
    }
}
