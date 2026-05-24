<?php

declare(strict_types=1);

namespace Tests\Unit\Users\Usecases;

use App\Features\Users\Application\Outputs\StoreUserOutput;
use App\Features\Users\Application\Usecases\StoreUserUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use App\Shared\Domain\Repositories\UserRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class StoreUserTest extends TestCase
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
    public function test_it_store_user_record(): void
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $groupRepository = Mockery::mock(GroupRepository::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $presenter = Mockery::mock(StoreUserOutput::class);

        $userId = 1;
        $userEntity = new UserEntity(
            id: $userId,
            name: 'user',
            email: 'user@mail.com',
        );
        $groupEntity = new GroupEntity(
            id: 1,
            name: 'test group name',
        );

        $userRepository
            ->shouldReceive('store')
            ->once()
            ->with($userEntity)
            ->andReturn($userEntity);

        $groupRepository
            ->shouldReceive('showByUserId')
            ->once()
            ->with($userId)
            ->andReturn($groupEntity);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::CREATE_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($userEntity);

        $usecase = new StoreUserUsecase(
            $userRepository,
            $groupRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($userEntity, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_store_user_permission()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $groupRepository = Mockery::mock(GroupRepository::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $presenter = Mockery::mock(StoreUserOutput::class);

        $userEntity = new UserEntity(
            id: 1,
            name: 'user',
            email: 'user@mail.com',
        );
        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::CREATE_USER)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new StoreUserUsecase(
            $userRepository,
            $groupRepository,
            $permissionGateway,
            $currentUser,
        );

        $usecase->execute($userEntity, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $groupRepository = Mockery::mock(GroupRepository::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $presenter = Mockery::mock(StoreUserOutput::class);

        $userEntity = new UserEntity(
            id: 1,
            name: 'user',
            email: 'user@mail.com',
        );

        $userRepository
            ->shouldReceive('store')
            ->once()
            ->with($userEntity)
            ->andThrow(new Exception('database error'));

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::CREATE_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new StoreUserUsecase(
            $userRepository,
            $groupRepository,
            $permissionGateway,
            $currentUser,
        );

        $usecase->execute($userEntity, $presenter);
    }
}
