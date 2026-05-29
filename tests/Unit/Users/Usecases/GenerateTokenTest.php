<?php

declare(strict_types=1);

namespace Tests\Unit\Users\Usecases;

use App\Features\Users\Application\Outputs\GenerateTokenOutput;
use App\Features\Users\Application\Usecases\GenerateTokenUsecase;
use App\Shared\Application\Contracts\Utilities\PasswordHasherContract;
use App\Shared\Application\Contracts\Utilities\TokenGeneratorContract;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Repositories\UserRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class GenerateTokenTest extends TestCase
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
    public function test_it_destroys_current_active_token(): void
    {
        $userRepository = Mockery::mock(UserRepository::class);
        $passwordHasher = Mockery::mock(PasswordHasherContract::class);
        $tokenGenerator = Mockery::mock(TokenGeneratorContract::class);
        $presenter = Mockery::mock(GenerateTokenOutput::class);

        $user = new UserEntity(
            id: 1,
            password: 'hashed-password'
        );

        $userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->with('john@example.com')
            ->andReturn($user);

        $passwordHasher
            ->shouldReceive('check')
            ->once()
            ->with('password', 'hashed-password')
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
            'password',
            $presenter
        );
    }
    public function test_it_fails_when_email_or_password_is_empty(): void
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
    public function test_it_fails_when_credential_is_wrong(): void
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
    public function test_it_handles_unexpected_exception(): void
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
            ->once();

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
