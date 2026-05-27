<?php

declare(strict_types=1);

namespace  Tests\Unit\AppInfos\Usecases;

use App\Features\AppInfos\Application\Ouptputs\ShowAllAppInfoOutput;
use App\Features\AppInfos\Application\Usecases\ShowAllAppInfoUsecase;
use App\Shared\Domain\Entities\AppInfo\AppInfoEntity;
use App\Shared\Domain\Repositories\AppInfoRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Exception;

final class ShowAllAppInfoTest extends TestCase
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
    public function test_it_shows_category_record(): void
    {
        $appInfoRepository = Mockery::mock(AppInfoRepository::class);
        $presenter = Mockery::mock(ShowAllAppInfoOutput::class);

        $appInfoEntities = [new AppInfoEntity()];

        $appInfoRepository
            ->shouldReceive('index')
            ->once()
            ->andReturn($appInfoEntities);

        $presenter
            ->shouldReceive('onSuccess')
            ->once()
            ->with($appInfoEntities);

        $usecase = new ShowAllAppInfoUsecase($appInfoRepository);
        $usecase->execute($presenter);
    }

    public function test_it_handles_unexpected_exception()
    {
        $appInfoRepository = Mockery::mock(AppInfoRepository::class);
        $presenter = Mockery::mock(ShowAllAppInfoOutput::class);


        $appInfoRepository
            ->shouldReceive('index')
            ->once()
            ->andThrow(new exception('database error'));

        $presenter
            ->shouldReceive('onFailure')
            ->once()
            ->with('database error');

        $usecase = new ShowAllAppInfoUsecase($appInfoRepository);
        $usecase->execute($presenter);
    }
}
