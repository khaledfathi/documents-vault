<?php

namespace App\Features\Categories\Infrastructure\Providers;

use App\Features\Categories\Application\Contracts\DestroyCategoryContract;
use App\Features\Categories\Application\Contracts\PaginateCategoryContract;
use App\Features\Categories\Application\Contracts\ShowCategoryContract;
use App\Features\Categories\Application\Contracts\StoreCategoryContract;
use App\Features\Categories\Application\Contracts\UpdateCategoryContract;
use App\Features\Categories\Application\Usecases\DestroyCategoryUsecase;
use App\Features\Categories\Application\Usecases\PaginateCategoryUsecase;
use App\Features\Categories\Application\Usecases\ShowCategoryUsecase;
use App\Features\Categories\Application\Usecases\StoreCategoryUsecase;
use App\Features\Categories\Application\Usecases\UpdateCategoryUsecase;
use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StoreCategoryContract::class, StoreCategoryUsecase::class);
        $this->app->bind(ShowCategoryContract::class, ShowCategoryUsecase::class);
        $this->app->bind(UpdateCategoryContract::class, UpdateCategoryUsecase::class);
        $this->app->bind(DestroyCategoryContract::class, DestroyCategoryUsecase::class);
        $this->app->bind(PaginateCategoryContract::class, PaginateCategoryUsecase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
