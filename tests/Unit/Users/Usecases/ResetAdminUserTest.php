<?php

declare(strict_types=1);

namespace Tests\Unit\Users\Usecases;

use App\Features\Users\Application\Outputs\ResetAdminUserOutput;
use App\Features\Users\Application\Usecases\ResetAdminUserUsecase;
use App\Shared\Domain\Repositories\GroupRepository;
use App\Shared\Domain\Repositories\UserRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class ResetAdminUserTest extends TestCase
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
    public function test_it_resets_admin_user(): void
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $groupRepository = Mockery::mock(GroupRepository::class);
        $presenter = Mockery::mock(ResetAdminUserOutput::class);

        $userRepository
            ->shouldReceive('getRootUserId')
            ->once()
            ->andReturn(1);

        $groupRepository
            ->shouldReceive('getAdminGroupId')
            ->once()
            ->andReturn(1);

        $userRepository
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $presenter
            ->shouldReceive('onSuccess')
            ->once();

        $usecase = new ResetAdminUserUsecase(
            $userRepository,
            $groupRepository,
        );
        $usecase->execute($presenter);
    }
    public function test_it_handles_unexpected_exception()
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $groupRepository = Mockery::mock(GroupRepository::class);
        $presenter = Mockery::mock(ResetAdminUserOutput::class);

        $userRepository
            ->shouldReceive('getRootUserId')
            ->once()
            ->andReturn(1);

        $groupRepository
            ->shouldReceive('getAdminGroupId')
            ->once()
            ->andReturn(1);

        $userRepository
            ->shouldReceive('update')
            ->once()
            ->andThrow(new Exception('database error'));

        $presenter
            ->shouldReceive('onFailure')
            ->once();

        $usecase = new ResetAdminUserUsecase(
            $userRepository,
            $groupRepository,
        );
        $usecase->execute($presenter);
    }
}
