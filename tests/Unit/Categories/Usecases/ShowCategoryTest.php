<?php

declare(strict_types=1);

namespace Tests\Categories\Users\Usecases;

use App\Features\Categories\Application\Outputs\ShowCategoryOutput;
use App\Features\Categories\Application\Usecases\ShowCategoryUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\CategoryRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class ShowCategoryTest extends TestCase
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
    public function test_it_shows_category_record(): void
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowCategoryOutput::class);

        $categoryId = 1;
        $categoryEntity = new CategoryEntity(id: $categoryId);

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
            ->with(1, PermissionType::VIEW_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($categoryEntity);

        $usecase = new ShowCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryId, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_view_category_permission()
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowCategoryOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_CATEGORY)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new ShowCategoryUsecase(
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
        $presenter = Mockery::mock(ShowCategoryOutput::class);

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
            ->with(1, PermissionType::VIEW_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new ShowCategoryUsecase(
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
        $presenter = Mockery::mock(ShowCategoryOutput::class);

        $categoryId = 1;

        $categoryRepository
            ->shouldReceive('show')
            ->once()
            ->with($categoryId)
            ->andThrow(new Exception('database error'));

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new ShowCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryId, $presenter);
    }
}
