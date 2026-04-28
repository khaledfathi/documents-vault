<?php

declare(strict_types=1);

namespace App\Features\Categories\Presentation\API\Presenters;

use App\Features\Categories\Application\Outputs\UpdateCategoryOutput;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class UpdateCategoryPresenter implements UpdateCategoryOutput
{
    use PresenterTrait;
    public function onSuccess(): void
    {
        $this->response = fn() => response()->json([
            "success" => true,
            "message" => "Category Updated Successfuly",
        ], Response::HTTP_OK);
    }
    public function onNotFound(): void
    {
        $this->response = fn() => $this->notFoundResponse("category is not found");
    }
    public function handle()
    {
        return ($this->response)();
    }
}
