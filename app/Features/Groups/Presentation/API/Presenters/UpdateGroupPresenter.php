<?php

namespace App\Features\Groups\Presentation\API\Presenters;

use App\Features\Groups\Application\Outputs\UpdateGroupOutput;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class UpdateGroupPresenter implements UpdateGroupOutput
{
    use PresenterTrait;
    public function onSuccess(): void
    {
        $this->response = fn() => response()->json([
            "success" => true,
            "message" => "Group Updated Successfuly",
        ], Response::HTTP_OK);
    }
    public function onAdminGroup(): void
    {
        $this->response = fn() => response()->json([
            'success' => false,
            'message' => 'can not update the default groups (admin , default)',
        ]);
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
