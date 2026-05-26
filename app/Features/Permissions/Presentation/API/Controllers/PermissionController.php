<?php

declare(strict_types=1);

namespace App\Features\Permissions\Presentation\API\Controllers;

use App\Features\Permissions\Application\Contracts\ListPermissionsContract;
use App\Features\Permissions\Presentation\API\Presenters\ListPermissionsPresenter;
use App\Shared\Presentation\HTTP\Controller;

final class PermissionController extends Controller
{
    public function __construct(
        private readonly ListPermissionsContract $listPermissionUsecase,
    ) {}
    public function index()
    {
        $presenter = new ListPermissionsPresenter();
        $this->listPermissionUsecase->execute($presenter);
        return $presenter->handle();
    }
}
