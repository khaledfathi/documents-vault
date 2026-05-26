<?php

declare(strict_types=1);

namespace Tests\Unit\Documents\Usecases;

use App\Features\Documents\Application\Outputs\ShowDocumentFileOutput;
use App\Features\Documents\Application\Usecases\ShowDocumentFileUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Document\FileEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\FileRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class ShowDocumentFileTest extends TestCase
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
    public function test_it_show_public_document_file_record(): void
    {
        $fileRrepository = Mockery::mock(FileRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowDocumentFileOutput::class);

        $fileEntity = new FileEntity();

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

        $fileRrepository
            ->shouldReceive('showWithRelationPublicOnly')
            ->once()
            ->with(1)
            ->andReturn($fileEntity);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($fileEntity);

        $usecase = new ShowDocumentFileUsecase(
            $fileRrepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_show_public_and_private_document_file_record(): void
    {
        $fileRrepository = Mockery::mock(FileRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowDocumentFileOutput::class);

        $fileEntity = new FileEntity();

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

        $fileRrepository
            ->shouldReceive('showWithRelation')
            ->once()
            ->with(1)
            ->andReturn($fileEntity);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($fileEntity);

        $usecase = new ShowDocumentFileUsecase(
            $fileRrepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_view_document_permission()
    {
        $fileRrepository = Mockery::mock(FileRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowDocumentFileOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_DOCUMENT)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once()
            ->with();

        $usecase = new ShowDocumentFileUsecase(
            $fileRrepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_document_file_record_is_not_found()
    {
        $fileRrepository = Mockery::mock(FileRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowDocumentFileOutput::class);

        $fileEntity = new FileEntity();

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

        $fileRrepository
            ->shouldReceive('showWithRelationPublicOnly')
            ->once()
            ->with(1)
            ->andReturn(null);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new ShowDocumentFileUsecase(
            $fileRrepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $fileRrepository = Mockery::mock(FileRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowDocumentFileOutput::class);

        $fileEntity = new FileEntity();

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

        $fileRrepository
            ->shouldReceive('showWithRelationPublicOnly')
            ->once()
            ->with(1)
            ->andThrow(new Exception('database error'));

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new ShowDocumentFileUsecase(
            $fileRrepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute(1, $presenter);
    }
}
