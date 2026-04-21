<?php

declare(strict_types=1);

namespace App\Features\Groups\Presentation\API\Presenters;

use App\Features\Groups\Application\Outputs\ShowGroupOutput;
use App\Shared\Domain\Entities\Group\GroupEntity;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class ShowGroupPresenter implements ShowGroupOutput
{
    use PresenterTrait;

    public function onSuccess(GroupEntity $groupEntity): void
    {
        $this->response = fn() => response()->json([
            'success' => true,
            'data' => $groupEntity->toArray(),
        ], Response::HTTP_OK);
    }
    public function onNotFound(): void
    {
        $this->response = fn() => $this->notFoundResponse('Group is not found');
    }
    public function handle()
    {
        return ($this->response)();
    }
}
