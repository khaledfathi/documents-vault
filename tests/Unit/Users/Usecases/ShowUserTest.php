<?php

declare(strict_types=1);

namespace Tests\Unit\Users\Usecases;

use App\Features\Users\Application\Outputs\ShowUserOutput;
use App\Features\Users\Application\Usecases\ShowUserUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\UserRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class ShowUserTest extends TestCase
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
    public function test_it_shows_user_record(): void
    {
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $userRepository = Mockery::mock(UserRepository::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowUserOutput::class);

        $user = new UserEntity(
            id: 1,
            name: 'user',
            email: 'user@mail.com',
        );

        $userRepository
            ->shouldReceive('show')
            ->once()
            ->with(1)
            ->andReturn($user);

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
            ->with($user);

        $usecase = new ShowUserUsecase(
            $userRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_view_user_permission()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowUserOutput::class);

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

        $usecase = new ShowUserUsecase(
            $userRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_fails_when_user_record_is_not_found()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowUserOutput::class);

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
            ->with(1, PermissionType::VIEW_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onNotFound')
            ->once();

        $usecase = new ShowUserUsecase(
            $userRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute(1, $presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ShowUserOutput::class);

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
            ->with(1, PermissionType::VIEW_USER)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new ShowUserUsecase(
            $userRepository,
            $permissionGateway,
            $currentUser,
        );
        $usecase->execute(1, $presenter);
    }
}
