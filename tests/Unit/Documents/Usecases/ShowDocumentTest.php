<?php

declare(strict_types=1);

namespace Tests\Unit\Users\Usecases;

use App\Features\Documents\Application\Outputs\ShowDocumentOutput;
use App\Features\Documents\Application\Usecases\ShowDocumentUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\DocumentRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class ShowDocumentTest extends TestCase
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
    public function test_it_shows_public_document_record(): void
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowDocumentOutput::class);

        $documentId = 1;
        $documentEntity = new DocumentEntity(id: $documentId,);

        $documentRepository
            ->shouldReceive('showWithRelationPublicOnly')
            ->once()
            ->with($documentId)
            ->andReturn($documentEntity);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_DOCUMENT)
            ->andReturn(true);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_ALL_DOCUMENT)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($documentEntity);

        $usecase = new ShowDocumentUsecase(
            $documentRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($documentId, $presenter);
    }
    public function test_it_shows_public_and_private_document_record(): void
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowDocumentOutput::class);

        $documentId = 1;
        $documentEntity = new DocumentEntity(id: $documentId,);

        $documentRepository
            ->shouldReceive('showWithRelation')
            ->once()
            ->with($documentId)
            ->andReturn($documentEntity);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_DOCUMENT)
            ->andReturn(true);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_ALL_DOCUMENT)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($documentEntity);

        $usecase = new ShowDocumentUsecase(
            $documentRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($documentId, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_view_document_permission()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowDocumentOutput::class);

        $documentId = 1;

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_DOCUMENT)
            ->once()
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new ShowDocumentUsecase(
            $documentRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($documentId, $presenter);
    }
    public function test_it_fails_when_document_record_is_not_found()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowDocumentOutput::class);

        $documentId = 1;

        $documentRepository
            ->shouldReceive('showWithRelation')
            ->once()
            ->with($documentId)
            ->andReturn(null);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_DOCUMENT)
            ->andReturn(true);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_ALL_DOCUMENT)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new ShowDocumentUsecase(
            $documentRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($documentId, $presenter);
    }

    public function test_it_handles_unexpected_exception()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowDocumentOutput::class);

        $documentId = 1;

        $documentRepository
            ->shouldReceive('showWithRelation')
            ->once()
            ->with($documentId)
            ->andThrow(new Exception('database error'));

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_DOCUMENT)
            ->andReturn(true);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_ALL_DOCUMENT)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new ShowDocumentUsecase(
            $documentRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($documentId, $presenter);
    }
}
