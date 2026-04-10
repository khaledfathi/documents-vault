<?php

declare(strict_types=1);

namespace App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\UpdateUserOutput;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class UpdateUserPresenter implements UpdateUserOutput
{
    use PresenterTrait;

    public function onSuccess(bool $stauts): void
    {
        $this->response = fn() => response()->json([
            "success" => true,
            "message" => "User Updated Successfuly",
        ], Response::HTTP_OK);
    }
    public function onNotFound(): void
    {
        $this->response = fn() => $this->notFoundResponse("user is not found");
    }
    public function handle()
    {
        return ($this->response)();
    }
}
