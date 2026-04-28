<?php

declare(strict_types=1);

namespace app\Features\Categories\Presentation\API\Controllers;

use App\Features\Categories\Application\Contracts\DestroyCategoryContract;
use App\Features\Categories\Application\Contracts\PaginateCategoryContract;
use App\Features\Categories\Application\Contracts\ShowCategoryContract;
use App\Features\Categories\Application\Contracts\StoreCategoryContract;
use App\Features\Categories\Application\Contracts\UpdateCategoryContract;
use App\Features\Categories\Presentation\API\Presenters\DestroyCategoryPresenter;
use App\Features\Categories\Presentation\API\Presenters\PaginateCategoryPresenter;
use App\Features\Categories\Presentation\API\Presenters\ShowCategoryPresneter;
use app\Features\Categories\Presentation\API\Presenters\StoreCategoryPresenter;
use App\Features\Categories\Presentation\API\Presenters\UpdateCategoryPresenter;
use App\Features\Categories\Presentation\API\Requests\StoreCategoryRequest;
use App\Features\Categories\Presentation\API\Requests\UpdateCategoryRequest;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Presentation\HTTP\Controller;
use Illuminate\Http\Request;

final class CategoryController extends Controller
{
    public function __construct(
        private readonly StoreCategoryContract $storeCategoryUsecase,
        private readonly ShowCategoryContract $showCategoryUsecase,
        private readonly UpdateCategoryContract $updateCategoryUsecase,
        private readonly DestroyCategoryContract $destroyCategoryUsecase,
        private readonly PaginateCategoryContract $paginateCategoryUsecase,
    ) {}
    public function index(Request $request)
    {
        $presenter = new PaginateCategoryPresenter();
        $this->paginateCategoryUsecase->execute($presenter, (int)($request->per_page ?? 10));
        return $presenter->handle();
    }
    public function show(string $categoryId)
    {
        $presneter = new ShowCategoryPresneter();
        $this->showCategoryUsecase->execute((int) $categoryId, $presneter);
        return $presneter->handle();
    }
    public function store(StoreCategoryRequest $request)
    {
        $presneter =  new StoreCategoryPresenter();
        $this->storeCategoryUsecase->execute($this->requestToCategoryEntity($request), $presneter);
        return $presneter->handle();
    }
    public function update(UpdateCategoryRequest $request, string $categoryId)
    {
        $presenter = new UpdateCategoryPresenter();
        $this->updateCategoryUsecase->execute($this->requestToCategoryEntity($request), $presenter);
        return $presenter->handle();
    }
    public function destroy(string $categoryId)
    {
        $presnter = new DestroyCategoryPresenter();
        $this->destroyCategoryUsecase->execute((int)$categoryId, $presnter);
        return $presnter->handle();
    }
    private function requestToCategoryEntity(Request $request): CategoryEntity
    {
        $categoryEntity = new CategoryEntity(
            name: $request->name,
            description: $request->description,
        );
        if ($categoryId = $request->route('category')) {
            $categoryEntity->id = (int) $categoryId;
        } elseif ($categoryId = $request->id) {
            $categoryEntity->id = (int) $categoryId;
        }
        return $categoryEntity;
    }
}
