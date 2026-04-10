<?php

declare(strict_types=1);

namespace App\Features\Permissions\Presentation\API\Presenter;

use App\Features\Permissions\Application\Outputs\ListPermissionsOutput;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class ListPermissionsPresenter implements ListPermissionsOutput
{
    use PresenterTrait;

    public function onSuccess(array $permissionEntities): void
    {
        $this->response = fn() => response()->json([
            'status' => true,
            'data' => $permissionEntities,
        ], Response::HTTP_OK);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
