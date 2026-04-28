<?php

declare(strict_types=1);

namespace app\Features\Categories\Presentation\API\Presenters;

use App\Features\Categories\Application\Outputs\StoreCategoryOutput;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class StoreCategoryPresenter implements StoreCategoryOutput
{
    use PresenterTrait;
    public function onSuccess(CategoryEntity $categoryEntity): void
    {
        $this->response = fn() => response()->json([
            'success' => true,
            'message' => 'category has been created successfully',
            'data' => $categoryEntity->toArray(),
        ], Response::HTTP_CREATED);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
