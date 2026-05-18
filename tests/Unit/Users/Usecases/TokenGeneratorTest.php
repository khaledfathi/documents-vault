<?php

declare(strict_types=1);

namespace App\Unit\Users\Usecases;

use App\Features\Users\Application\Outputs\GenerateTokenOutput;
use App\Features\Users\Application\Usecases\GenerateTokenUsecase;
use App\Shared\Application\Contracts\Utilities\PasswordHasherContract;
use App\Shared\Application\Contracts\Utilities\TokenGeneratorContract;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Repositories\UserRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class TokenGeneratorTest extends TestCase
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
    public function test_on_success(): void
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $passwordHasher = Mockery::mock(PasswordHasherContract::class);
        $tokenGenerator = Mockery::mock(TokenGeneratorContract::class);
        $presenter = Mockery::mock(GenerateTokenOutput::class);

        $user = new UserEntity();
        $user->id = 1;
        $user->password = 'hashed-password';

        $userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->with('john@example.com')
            ->andReturn($user);

        $passwordHasher
            ->shouldReceive('check')
            ->once()
            ->with('secret', 'hashed-password')
            ->andReturn(true);

        $tokenGenerator
            ->shouldReceive('generate')
            ->once()
            ->with(1)
            ->andReturn('generated-token');

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with('generated-token');

        $usecase = new GenerateTokenUsecase(
            $userRepository,
            $passwordHasher,
            $tokenGenerator
        );

        $usecase->execute(
            'john@example.com',
            'secret',
            $presenter
        );
    }

    public function test__on_missing_input(): void
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $passwordHasher = Mockery::mock(PasswordHasherContract::class);
        $tokenGenerator = Mockery::mock(TokenGeneratorContract::class);
        $presenter = Mockery::mock(GenerateTokenOutput::class);

        $presenter
            ->shouldReceive('onMissingInput')
            ->once()
            ->with('missing inputs : email or password is not provided');

        $usecase = new GenerateTokenUsecase(
            $userRepository,
            $passwordHasher,
            $tokenGenerator
        );

        $usecase->execute('', '', $presenter);
    }

    public function test_on_credential_failed(): void
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $passwordHasher = Mockery::mock(PasswordHasherContract::class);
        $tokenGenerator = Mockery::mock(TokenGeneratorContract::class);
        $presenter = Mockery::mock(GenerateTokenOutput::class);

        $userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->andReturn(null);

        $presenter
            ->shouldReceive('onCredentialFailed')
            ->once();

        $usecase = new GenerateTokenUsecase(
            $userRepository,
            $passwordHasher,
            $tokenGenerator
        );

        $usecase->execute(
            'john@example.com',
            'wrong-password',
            $presenter
        );
    }

    public function test_on_failure(): void
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $passwordHasher = Mockery::mock(PasswordHasherContract::class);
        $tokenGenerator = Mockery::mock(TokenGeneratorContract::class);
        $presenter = Mockery::mock(GenerateTokenOutput::class);

        $userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->andThrow(new Exception('database error'));

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new GenerateTokenUsecase(
            $userRepository,
            $passwordHasher,
            $tokenGenerator
        );

        $usecase->execute(
            'john@example.com',
            'secret',
            $presenter
        );
    }
}
