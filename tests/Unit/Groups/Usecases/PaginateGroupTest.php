<?php

declare(strict_types=1);

namespace Tests\Unit\Users\Usecases;

use App\Features\Groups\Application\Outputs\PaginateGroupOutput;
use App\Features\Groups\Application\Usecases\PaginateGroupUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class PaginateGroupTest extends TestCase
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
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateGroupOutput::class);

        $entitiesWithPagination = new EntitiesWithPagination();
        $groupRepository
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
            ->with(1, PermissionType::VIEW_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($entitiesWithPagination);

        $usecase = new PaginateGroupUsecase(
            $groupRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_view_group_permission()
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateGroupOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::VIEW_GROUP)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new PaginateGroupUsecase(
            $groupRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(PaginateGroupOutput::class);

        $entitiesWithPagination = new EntitiesWithPagination();
        $groupRepository
            ->shouldReceive('paginate')
            ->once()
            ->with(10)
            ->andThrow(new Exception('database error'));

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::VIEW_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new PaginateGroupUsecase(
            $groupRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($presenter);
    }
}
