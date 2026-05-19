<?php

declare(strict_types=1);

namespace App\Unit\Users\Usecases;

use App\Features\Users\Application\Outputs\UpdateUserOutput;
use App\Features\Users\Application\Usecases\UpdateUserUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\UserRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class UpdateUserTest extends TestCase
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
    public function test_it_updates_user_record(): void
    {
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $userRepository = Mockery::mock(UserRepository::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateUserOutput::class);

        $userEntity = new UserEntity(
            id: 1,
            name: 'user',
            email: 'user@mail.com',
        );

        $userRepository
            ->shouldReceive('update')
            ->once()
            ->with($userEntity)
            ->andReturn(true);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::EDIT_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with(true);

        $usecase = new UpdateUserUsecase(
            $userRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($userEntity, $presenter);
    }

    public function test_it_fails_when_current_user_doesnt_have_edit_user_permission()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateUserOutput::class);

        $userEntity = new UserEntity(
            id: 1,
            name: 'user',
        );

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::EDIT_USER)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new UpdateUserUsecase(
            $userRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($userEntity, $presenter);
    }

    public function test_it_fails_when_user_recoed_is_not_found(): void
    {
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $userRepository = Mockery::mock(UserRepository::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateUserOutput::class);

        $userEntity = new UserEntity(
            id: 1,
            name: 'user',
            email: 'user@mail.com',
        );

        $userRepository
            ->shouldReceive('update')
            ->once()
            ->with($userEntity)
            ->andReturn(false);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::EDIT_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new UpdateUserUsecase(
            $userRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($userEntity, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(UpdateUserOutput::class);

        $userEntity = new UserEntity(
            id: 1,
            name: 'user',
            email: 'user@mail.com',
        );

        $userRepository
            ->shouldReceive('update')
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
            ->with(1, PermissionType::EDIT_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new UpdateUserUsecase(
            $userRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute($userEntity, $presenter);
    }
}
