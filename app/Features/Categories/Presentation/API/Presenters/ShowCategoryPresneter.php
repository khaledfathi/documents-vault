<?php

declare(strict_types=1);

namespace App\Features\Categories\Presentation\API\Presenters;

use App\Features\Categories\Application\Outputs\ShowCategoryOutput;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class ShowCategoryPresneter implements ShowCategoryOutput
{
    use PresenterTrait;
    public function onSuccess(categoryEntity $categoryEntity): void
    {
        $this->response = fn() => response()->json([
            "success" => true,
            "message" => "",
            'data' => $categoryEntity->toArray(),
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
