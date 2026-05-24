<?php

declare(strict_types=1);

namespace Tests\Unit\Users\Usecases;

use App\Features\Groups\Application\Outputs\DestroyGroupOutput;
use App\Features\Groups\Application\Usecases\DestroyGroupUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class DestroyGroupTest extends TestCase
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
    public function test_it_destroy_group_record(): void
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyGroupOutput::class);

        $groupRepository
            ->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn(new GroupEntity());

        $groupRepository
            ->shouldReceive('destroy')
            ->once()
            ->with(1)
            ->andReturn(true);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::DELETE_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once();

        $usecase = new DestroyGroupUsecase(
            $groupRepository,
            $currentUser,
            $permissionGateway,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_destroy_group_permission()
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyGroupOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::DELETE_GROUP)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new DestroyGroupUsecase(
            $groupRepository,
            $currentUser,
            $permissionGateway,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_group_record_is_not_found()
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyGroupOutput::class);

        $groupRepository
            ->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn(null);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::DELETE_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new DestroyGroupUsecase(
            $groupRepository,
            $currentUser,
            $permissionGateway,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_trying_destroy_protected_groups(): void
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyGroupOutput::class);

        $groupRepository
            ->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn(new GroupEntity(isProtected:true));

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::DELETE_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onProtectedGroup')
            ->once();

        $usecase = new DestroyGroupUsecase(
            $groupRepository,
            $currentUser,
            $permissionGateway,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyGroupOutput::class);

        $groupRepository
            ->shouldReceive('show')
            ->once()
            ->with(1)
            ->andThrow(new Exception('database error'));

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::DELETE_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new DestroyGroupUsecase(
            $groupRepository,
            $currentUser,
            $permissionGateway,
        );
        $usecase->execute(1, $presenter);
    }
}
