<?php

declare(strict_types=1);

namespace App\Features\Groups\Presentation\API\Presenters;

use App\Features\Groups\Application\Outputs\StoreGroupOutput;
use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class StoreGroupPresenter implements StoreGroupOutput
{
    use PresenterTrait;

    public function onSuccess(GroupEntity $groupEntity): void
    {
        $this->response = fn() => response()->json([
            'success' => true,
            'data' => $groupEntity,
        ], Response::HTTP_OK);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
