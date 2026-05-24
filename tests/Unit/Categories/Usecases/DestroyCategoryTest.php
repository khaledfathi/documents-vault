<?php

declare(strict_types=1);

namespace Tests\Categories\Users\Usecases;

use App\Features\Categories\Application\Outputs\DestroyCategoryOutput;
use App\Features\Categories\Application\Usecases\DestroyCategoryUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\CategoryRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class DestroyCategoryTest extends TestCase
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
    public function test_it_destroy_category_record(): void
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyCategoryOutput::class);

        $categoryId = 1;
        $categoryEntity = new CategoryEntity(id: $categoryId);

        $categoryRepository
            ->shouldReceive('show')
            ->once()
            ->with($categoryId)
            ->andReturn($categoryEntity);

        $categoryRepository
            ->shouldReceive('destroy')
            ->once()
            ->with($categoryId)
            ->andReturn(true);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::DELETE_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once();

        $usecase = new DestroyCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryId, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_destroy_category_permission()
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyCategoryOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::DELETE_CATEGORY)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new DestroyCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_category_record_is_not_found()
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyCategoryOutput::class);

        $categoryId = 1;
        $categoryEntity = new CategoryEntity(id: $categoryId);

        $categoryRepository
            ->shouldReceive('show')
            ->once()
            ->with($categoryId)
            ->andReturn(null);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::DELETE_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new DestroyCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryId, $presenter);
    }

    public function test_it_fails_when_trying_to_delete_default_group()
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyCategoryOutput::class);

        $categoryId = 1;
        $categoryEntity = new CategoryEntity(id: $categoryId, isDefaultGroup: true);

        $categoryRepository
            ->shouldReceive('show')
            ->once()
            ->with($categoryId)
            ->andReturn($categoryEntity);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::DELETE_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onDefaultGroup')
            ->once();

        $usecase = new DestroyCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryId, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyCategoryOutput::class);

        $categoryId = 1;
        $categoryEntity = new CategoryEntity(id: $categoryId);

        $categoryRepository
            ->shouldReceive('show')
            ->once()
            ->with($categoryId)
            ->andReturn($categoryEntity);

        $categoryRepository
            ->shouldReceive('destroy')
            ->once()
            ->with($categoryId)
            ->andThrow(new Exception('database error'));

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::DELETE_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new DestroyCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryId, $presenter);
    }
}
