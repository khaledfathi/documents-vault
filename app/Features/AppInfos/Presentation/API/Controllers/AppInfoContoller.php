<?php

declare(strict_types=1);

namespace App\Features\AppInfos\Presentation\API\Controllers;

use App\Features\AppInfos\Application\Contracts\ShowAllAppInfoContract;
use App\Features\AppInfos\Presentation\API\Presenter\ShowAllAppInfoPresenter;
use App\Shared\Presentation\HTTP\Controller;

final class AppInfoContoller extends Controller
{

    public function __construct(
        private readonly ShowAllAppInfoContract $showAllAppInfoUsecase,
    ) {}
    public function index()
    {
        $presenter = new ShowAllAppInfoPresenter();
        $this->showAllAppInfoUsecase->execute($presenter);
        return $presenter->handle();
    }
}
