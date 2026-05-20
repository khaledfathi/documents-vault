<?php

declare(strict_types=1);

namespace App\Unit\Permissions\Usecases;

use App\Features\Permissions\Application\Outputs\ListPermissionsOutput;
use App\Features\Permissions\Application\Usecases\ListPermissionsUsecase;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Group\PermissionEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\PermissionRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class ListPermissionsTest extends TestCase
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
    public function test_it_shows_permissions_list(): void
    {
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $permissionRepository = Mockery::mock(PermissionRepository::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ListPermissionsOutput::class);

        $permissionEntities = [
            new PermissionEntity(id: 1),
            new PermissionEntity(id: 2),
        ];

        $permissionRepository
            ->shouldReceive('index')
            ->once()
            ->andReturn($permissionEntities);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::VIEW_PERMISSION)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($permissionEntities);

        $usecase = new ListPermissionsUsecase(
            $currentUser,
            $permissionGateway,
            $permissionRepository,
        );
        $usecase->execute($presenter);
    }
    public function test_it_fails_when_current_user_doesnt_have_view_permissions_permission()
    {
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $permissionRepository = Mockery::mock(PermissionRepository::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ListPermissionsOutput::class);

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::VIEW_PERMISSION)
            ->andReturn(false);

        $presenter
            ->shouldReceive('onUnauthorized')
            ->once()
            ->with();

        $usecase = new ListPermissionsUsecase(
            $currentUser,
            $permissionGateway,
            $permissionRepository,
        );
        $usecase->execute($presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $permissionGateway = Mockery::mock(PermissionGateway::class);
        $permissionRepository = Mockery::mock(PermissionRepository::class);
        $currentUser = Mockery::mock(CurrentUserContract::class);
        $presenter = Mockery::mock(ListPermissionsOutput::class);

        $permissionRepository
            ->shouldReceive('index')
            ->once()
            ->andThrow(new Exception('database error'));

        $currentUser
            ->shouldReceive('id')
            ->once()
            ->andReturn(1);

        $permissionGateway
            ->shouldReceive('can')
            ->once()
            ->with(1, PermissionType::VIEW_PERMISSION)
            ->andReturn(true);

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new ListPermissionsUsecase(
            $currentUser,
            $permissionGateway,
            $permissionRepository,
        );
        $usecase->execute($presenter);
    }
}
