<?php

declare(strict_types=1);

namespace Tests\Unit\Users\Usecases;

use App\Features\Users\Application\Outputs\PaginateUsersOutput;
use App\Features\Users\Application\Usecases\PaginateUsersUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\UserRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Domain\ValuObjects\Pagination;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class PaginateUserTest extends TestCase
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
    public function test_it_paginates_users_records(): void
    {
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $userRepository = Mockery::mock(UserRepository::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateUsersOutput::class);

        $users = [];
        for ($i = 0; $i < 2; $i++) {
            $users[] = new UserEntity(
                id: $i,
                name: 'test',
            );
        }
        $entitiesWithPagination = new EntitiesWithPagination(
            new Pagination(),
            $users
        );

        $userRepository
            ->shouldReceive('paginate')
            ->once()
            ->with(10)
            ->andReturn($entitiesWithPagination);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::VIEW_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($entitiesWithPagination);

        $usecase = new PaginateUsersUsecase(
            $userRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($presenter, 10);
    }
    public function test_it_fails_when_current_user_doesnt_have_view_user_permission()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateUsersOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::VIEW_USER)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new PaginateUsersUsecase(
            $userRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($presenter, 10);
    }
    public function test_it_handles_unexpected_exception()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateUsersOutput::class);

        $perPage = 10;

        $userRepository
            ->shouldReceive('paginate')
            ->once()
            ->with($perPage)
            ->andThrow(new Exception('database error'));

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::VIEW_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new PaginateUsersUsecase(
            $userRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($presenter, $perPage);
    }
}
