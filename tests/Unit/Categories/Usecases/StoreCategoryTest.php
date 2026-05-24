<?php

declare(strict_types=1);

namespace Tests\Categories\Users\Usecases;

use App\Features\Categories\Application\Outputs\StoreCategoryOutput;
use App\Features\Categories\Application\Usecases\StoreCategoryUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\CategoryRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class StoreCategoryTest extends TestCase
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
    public function test_it_stores_category_record(): void
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(StoreCategoryOutput::class);

        $categoryEntity = new CategoryEntity();

        $categoryRepository
            ->shouldReceive('store')
            ->once()
            ->with($categoryEntity)
            ->andReturn($categoryEntity);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::CREATE_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($categoryEntity);

        $usecase = new StoreCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryEntity, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_store_category_permission()
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(StoreCategoryOutput::class);

        $categoryEntity = new CategoryEntity();

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::CREATE_CATEGORY)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new StoreCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryEntity, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(StoreCategoryOutput::class);

        $categoryEntity = new CategoryEntity();

        $categoryRepository
            ->shouldReceive('store')
            ->once()
            ->with($categoryEntity)
            ->andThrow(new Exception('database error'));

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::CREATE_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new StoreCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryEntity, $presenter);
    }
}
