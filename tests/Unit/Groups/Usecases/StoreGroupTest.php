<?php

declare(strict_types=1);

namespace App\Unit\Users\Usecases;

use App\Features\Groups\Application\Outputs\StoreGroupOutput;
use App\Features\Groups\Application\Usecases\StoreGroupUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class StoreGroupTest extends TestCase
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
    public function test_it_stores_group_record(): void
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(StoreGroupOutput::class);

        $groupEntity = new GroupEntity(
            id: 1,
            name: 'group test name',
        );

        $groupRepository
            ->shouldReceive('store')
            ->once()
            ->with($groupEntity)
            ->andReturn($groupEntity);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::CREATE_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($groupEntity);

        $usecase = new StoreGroupUsecase(
            $groupRepository,
            $currentUser,
            $permissionGateway,
        );
        $usecase->execute($groupEntity, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_store_group_permission()
    {
        $groupEntity = new GroupEntity(
            id: 1,
            name: 'group test name',
        );
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(StoreGroupOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::CREATE_GROUP)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new StoreGroupUsecase(
            $groupRepository,
            $currentUser,
            $permissionGateway,
        );
        $usecase->execute($groupEntity, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $groupRepository = Mockery::mock(GroupRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(StoreGroupOutput::class);

        $groupEntity = new GroupEntity(
            id: 1,
            name: 'group test name',
        );

        $groupRepository
            ->shouldReceive('store')
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
            ->with(1, PermissionType::CREATE_GROUP)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new StoreGroupUsecase(
            $groupRepository,
            $currentUser,
            $permissionGateway,
        );
        $usecase->execute($groupEntity, $presenter);
    }
}
