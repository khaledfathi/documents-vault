<?php

declare(strict_types=1);

namespace Tests\Categories\Users\Usecases;

use App\Features\Categories\Application\Outputs\UpdateCategoryOutput;
use App\Features\Categories\Application\Usecases\StoreCategoryUsecase;
use App\Features\Categories\Application\Usecases\UpdateCategoryUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\CategoryRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class UpdateCategoryTest extends TestCase
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
    public function test_it_updates_category_record(): void
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateCategoryOutput::class);

        $categoryEntity = new CategoryEntity();

        $categoryRepository
            ->shouldReceive('update')
            ->once()
            ->with($categoryEntity)
            ->andReturn(true);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::EDIT_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with(true);

        $usecase = new UpdateCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryEntity, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_update_category_permission()
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateCategoryOutput::class);

        $categoryEntity = new CategoryEntity();

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::EDIT_CATEGORY)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new UpdateCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryEntity, $presenter);
    }
    public function test_it_fails_when_category_record_is_not_found()
    {
        $categoryRepository = Mockery::mock(CategoryRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateCategoryOutput::class);

        $categoryEntity = new CategoryEntity();

        $categoryRepository
            ->shouldReceive('update')
            ->once()
            ->with($categoryEntity)
            ->andReturn(false);

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::EDIT_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new UpdateCategoryUsecase(
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
        $presenter = Mockery::mock(UpdateCategoryOutput::class);

        $categoryEntity = new CategoryEntity();

        $categoryRepository
            ->shouldReceive('update')
            ->once()
            ->with($categoryEntity)
            ->andThrow(new Exception('database error'));

        $currentUser
            ->shouldReceive('id')
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->with(1, PermissionType::EDIT_CATEGORY)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once('database error')
            ->with(true);

        $usecase = new UpdateCategoryUsecase(
            $permissionGateway,
            $currentUser,
            $categoryRepository,
        );
        $usecase->execute($categoryEntity, $presenter);
    }
}
