<?php

declare(strict_types=1);

namespace Tests\Unit\Users\Usecases;

use App\Features\Groups\Application\Outputs\UpdateGroupOutput;
use App\Features\Groups\Application\Usecases\UpdateGroupUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class UpdateGroupTest extends TestCase
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
    public function test_it_updates_group_record(): void
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateGroupOutput::class);

        $groupEntity = new GroupEntity(
            id: 1,
            name: 'group test name',
        );

        $groupRepository
            ->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn($groupEntity);

        $groupRepository
            ->shouldReceive('update')
            ->once()
            ->with($groupEntity)
            ->andReturn(true);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::EDIT_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once();

        $usecase = new UpdateGroupUsecase(
            $groupRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($groupEntity, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_update_group_permission()
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateGroupOutput::class);

        $groupEntity = new GroupEntity(
            id: 1,
            name: 'group test name',
        );

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::EDIT_GROUP)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new UpdateGroupUsecase(
            $groupRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($groupEntity, $presenter);
    }
    public function test_it_fails_when_group_record_is_not_found()
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateGroupOutput::class);

        $groupEntity = new GroupEntity(
            id: 1,
            name: 'group test name',
        );

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
            ->with(1, PermissionType::EDIT_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new UpdateGroupUsecase(
            $groupRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($groupEntity, $presenter);
    }
    public function test_it_fails_when_trying_destroy_admin_group()
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateGroupOutput::class);

        $groupEntity = new GroupEntity(
            id: 1,
            name: 'group test name',
            isAdmin:true,
        );

        $groupRepository
            ->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn($groupEntity);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::EDIT_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onAdminGroup')
            ->once();

        $usecase = new UpdateGroupUsecase(
            $groupRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($groupEntity, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateGroupOutput::class);

        $groupEntity = new GroupEntity(
            id: 1,
            name: 'group test name',
        );

        $groupRepository
            ->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn($groupEntity);

        $groupRepository
            ->shouldReceive('update')
            ->once()
            ->with($groupEntity)
            ->andThrow(new Exception('database error'));

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::EDIT_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once();

        $usecase = new UpdateGroupUsecase(
            $groupRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($groupEntity, $presenter);
    }
}
