<?php

declare(strict_types=1);

namespace App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\DestroyUserOutput;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class DestroyUserPresenter implements DestroyUserOutput
{
    use PresenterTrait;

    public function onSuccess(): void
    {
        $this->response = fn() => response()->json([
            "success" => true,
            "message" => "User Destroyed Successfuly",
        ], Response::HTTP_OK);
    }
    public function onNotFound(): void
    {
        $this->response = fn() => $this->notFoundResponse("user is not found");
    }
    public function onAdminUser(): void
    {
        $this->response = fn() => response()->json([
            'success' => false,
            'message' => 'can not delete the root(admin) user',
        ]);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
