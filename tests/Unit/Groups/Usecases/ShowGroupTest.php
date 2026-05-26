<?php

declare(strict_types=1);

namespace Tests\Unit\Groups\Usecases;

use App\Features\Groups\Application\Outputs\ShowGroupOutput;
use App\Features\Groups\Application\Usecases\ShowGroupUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class ShowGroupTest extends TestCase
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
    public function test_it_shows_group_record(): void
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowGroupOutput::class);

        $groupId = 1;
        $groupEntity = new GroupEntity(
            id: $groupId,
            name: 'group test name',
        );

        $groupRepository
            ->shouldReceive('show')
            ->once()
            ->with($groupId)
            ->andReturn($groupEntity);

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
            ->with($groupEntity);

        $usecase = new ShowGroupUsecase(
            $groupRepository,
            $currentUser,
            $permissionGateway,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_view_group_permission()
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowGroupOutput::class);

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

        $usecase = new ShowGroupUsecase(
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
        $presenter = Mockery::mock(ShowGroupOutput::class);


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
            ->with(1, PermissionType::VIEW_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new ShowGroupUsecase(
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
        $presenter = Mockery::mock(ShowGroupOutput::class);

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
            ->with(1, PermissionType::VIEW_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new ShowGroupUsecase(
            $groupRepository,
            $currentUser,
            $permissionGateway,
        );
        $usecase->execute(1, $presenter);
    }
}
