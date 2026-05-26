<?php

declare(strict_types=1);

namespace Tests\Unit\Documents\Usecases;

use App\Features\Documents\Application\Outputs\PaginateDocumentOutput;
use App\Features\Documents\Application\Usecases\PaginateDocumentUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\DocumentRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Domain\ValuObjects\Pagination;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class PaginateDocumentTest extends TestCase
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
    public function test_it_paginates_public_documents_records(): void
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateDocumentOutput::class);

        $perPage = 10;
        $entitiesWithPagination = new EntitiesWithPagination(
            entities: [new DocumentEntity(), new DocumentEntity()],
            pagination: new Pagination(),
        );

        $documentRepository
            ->shouldReceive('paginatePublicDocumnetOnly')
            ->once()
            ->with($perPage)
            ->andReturn($entitiesWithPagination);

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
            ->with($entitiesWithPagination);

        $usecase = new PaginateDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $documentRepository,
        );
        $usecase->execute($presenter, $perPage);
    }
    public function test_it_paginates_public_and_private_documents_records(): void
    {

        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateDocumentOutput::class);

        $perPage = 10;
        $entitiesWithPagination = new EntitiesWithPagination(
            entities: [new DocumentEntity(), new DocumentEntity()],
            pagination: new Pagination(),
        );

        $documentRepository
            ->shouldReceive('paginate')
            ->once()
            ->with($perPage)
            ->andReturn($entitiesWithPagination);

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
            ->with($entitiesWithPagination);

        $usecase = new PaginateDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $documentRepository,
        );
        $usecase->execute($presenter, $perPage);
    }
    public function test_it_fails_when_current_user_doesnt_have_view_document_permission()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateDocumentOutput::class);

        $perPage = 10;

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_DOCUMENT)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new PaginateDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $documentRepository,
        );
        $usecase->execute($presenter, $perPage);
    }
    public function test_it_handles_unexpected_exception()
    {
        $documentRepository = Mockery::mock(DocumentRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateDocumentOutput::class);

        $perPage = 10;

        $documentRepository
            ->shouldReceive('paginatePublicDocumnetOnly')
            ->once()
            ->with($perPage)
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
            ->andReturn(false);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new PaginateDocumentUsecase(
            $permissionGateway,
            $currentUser,
            $documentRepository,
        );
        $usecase->execute($presenter, $perPage);
    }
}
