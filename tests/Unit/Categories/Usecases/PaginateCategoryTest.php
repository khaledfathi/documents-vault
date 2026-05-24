<?php

declare(strict_types=1);

namespace Tests\Categories\Users\Usecases;

use App\Features\Categories\Application\Outputs\PaginateCategoryOutput;
use App\Features\Categories\Application\Usecases\PaginateCategoryUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\CategoryRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Domain\ValuObjects\Pagination;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class PaginateCategoryTest extends TestCase
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
    public function test_it_paginates_categories_records(): void
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateCategoryOutput::class);

        $entitiyWithPagination = new EntitiesWithPagination(new Pagination(), [new CategoryEntity()]);
        $perPage = 10;

        $categoryRepository
            ->shouldReceive('paginate')
            ->once()
            ->with($perPage)
            ->andReturn($entitiyWithPagination);

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
            ->with($entitiyWithPagination);

        $usecase = new PaginateCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($presenter, $perPage);
    }
    public function test_it_fails_when_current_user_doesnt_have_view_category_permission()
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateCategoryOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::VIEW_CATEGORY)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onFailure')
            ->once();

        $usecase = new PaginateCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($presenter, 10);
    }
    public function test_it_handles_unexpected_exception()
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateCategoryOutput::class);

        $perPage = 10;

        $categoryRepository
            ->shouldReceive('paginate')
            ->once()
            ->with($perPage)
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
            ->once();

        $usecase = new PaginateCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($presenter, $perPage);
    }
}
