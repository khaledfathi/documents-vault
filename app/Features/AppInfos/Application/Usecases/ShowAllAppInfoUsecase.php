<?php

declare(strict_types=1);

namespace App\Features\AppInfos\Application\Usecases;

use App\Features\AppInfos\Application\Contracts\ShowAllAppInfoContract;
use App\Features\AppInfos\Application\Ouptputs\ShowAllAppInfoOutput;
use App\Shared\Domain\Repositories\AppInfoRepository;
use Exception;

final readonly class ShowAllAppInfoUsecase implements ShowAllAppInfoContract
{

    public function __construct(
        private AppInfoRepository $appInfoRepository,
    ) {}
    public function execute(ShowAllAppInfoOutput $presenter): void
    {
        try {
            $appInfoEntities = $this->appInfoRepository->index();
            $presenter->onSuccess($appInfoEntities);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
