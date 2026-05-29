<?php

declare(strict_types=1);

namespace Tests\Unit\Users\Usecases;

use App\Features\Users\Application\Outputs\DestroyUserOutput;
use App\Features\Users\Application\Usecases\DestroyUserUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\UserRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class DestroyUserTest extends TestCase
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
    public function test_it_destroys_user_record(): void
    {
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $userRepository = Mockery::mock(UserRepository::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyUserOutput::class);

        $userEntity = new UserEntity(
            id: 1,
            name: 'user'
        );
        $userRepository
            ->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn($userEntity);

        $userRepository
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
            ->with(1, PermissionType::DELETE_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once();

        $usecase = new DestroyUserUsecase(
            $currentUser,
            $permissionGateway,
            $userRepository,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_delete_user_permission()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyUserOutput::class);


        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::DELETE_USER)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once();

        $usecase = new DestroyUserUsecase(
            $currentUser,
            $permissionGateway,
            $userRepository,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_user_record_is_not_found()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyUserOutput::class);

        $userRepository
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
            ->with(1, PermissionType::DELETE_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new DestroyUserUsecase(
            $currentUser,
            $permissionGateway,
            $userRepository,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_trying_to_delete_root_user()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyUserOutput::class);

        $userEntity = new UserEntity(
            id: 1,
            name: 'user',
            isRoot: true,
        );

        $userRepository
            ->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn($userEntity);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::DELETE_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onRootUser')
            ->once();

        $usecase = new DestroyUserUsecase(
            $currentUser,
            $permissionGateway,
            $userRepository,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(DestroyUserOutput::class);

        $userRepository
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
            ->with(1, PermissionType::DELETE_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new DestroyUserUsecase(
            $currentUser,
            $permissionGateway,
            $userRepository,
        );
        $usecase->execute(1, $presenter);
    }
}
