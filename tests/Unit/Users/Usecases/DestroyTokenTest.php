<?php

declare(strict_types=1);

namespace Tests\Unit\Users\Usecases;

use App\Features\Users\Application\Outputs\DestroyTokenOutput;
use App\Features\Users\Application\Usecases\DestroyTokenUsecase;
use App\Shared\Application\Contracts\Utilities\TokenGeneratorContract;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

final class DestroyTokenTest extends TestCase
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
    public function test_it_generates_token_when_credentials_are_valid(): void
    {
        $presenter = Mockery::mock(DestroyTokenOutput::class);
        $tokenGenerator = Mockery::mock(TokenGeneratorContract::class);

        $tokenGenerator
            ->shouldReceive('destroyCurrentToken')
            ->once();

        $presenter
            ->shouldReceive('onSuccess')
            ->once();

        $usecase = new DestroyTokenUsecase(
            $tokenGenerator,
        );

        $usecase->execute(
            $presenter
        );
    }
    public function test_it_handles_unexpected_exception()
    {
        $tokenGenerator = Mockery::mock(TokenGeneratorContract::class);
        $presenter = Mockery::mock(DestroyTokenOutput::class);

        $tokenGenerator
            ->shouldReceive('destroyCurrentToken')
            ->once()
            ->andThrow(new Exception('failed to destroy token'));

        $presenter
            ->shouldReceive('onFailure')
            ->once();

        $usecase = new DestroyTokenUsecase(
            $tokenGenerator,
        );
        $usecase->execute(
            $presenter
        );
    }
}
