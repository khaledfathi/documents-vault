<?php

declare(strict_types=1);

namespace App\Features\Groups\Presentation\API\Presenters;

use App\Features\Groups\Application\Outputs\DestroyGroupOutput;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class DestroyGroupPresenter implements DestroyGroupOutput
{
    use PresenterTrait;

    public function onSuccess(): void
    {
        $this->response = fn() => response()->json([
            'success' => true,
            "message" => "group Destroyed Successfuly",
        ], Response::HTTP_OK);
    }
    public function onNotfound(): void
    {
        $this->response = fn() => $this->notFoundResponse("group is not found");
    }
    public function onDefaultGroups(): void
    {
        $this->response = fn() => response()->json([
            'success' => false,
            'message' => 'can not delete the default groups (admin , reader)',
        ]);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
