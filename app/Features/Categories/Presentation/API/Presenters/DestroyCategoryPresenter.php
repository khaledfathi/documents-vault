<?php

declare(strict_types=1);

namespace App\Features\Categories\Presentation\API\Presenters;

use App\Features\Categories\Application\Outputs\DestroyCategoryOutput;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class DestroyCategoryPresenter implements DestroyCategoryOutput
{
    use PresenterTrait;
    public function onSuccess(): void
    {
        $this->response = fn() => response()->json([
            "success" => true,
            "message" => "Category Destroyed Successfuly",
        ], Response::HTTP_OK);
    }
    public function onNotFound(): void
    {
        $this->response = fn() => $this->notFoundResponse("Category is not found");
    }
    public function onDefaultGroup(): void
    {
        $this->response = fn() => response()->json([
            'success' => false,
            'message' => 'can not delete the default category',
        ]);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
