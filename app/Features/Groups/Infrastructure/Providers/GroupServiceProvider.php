<?php

namespace App\Features\Groups\Infrastructure\Providers;

use App\Features\Groups\Application\Contracts\DestroyGroupContract;
use App\Features\Groups\Application\Contracts\ShowGroupContract;
use App\Features\Groups\Application\Contracts\StoreGroupContract;
use App\Features\Groups\Application\Usecases\DestroyGroupUsecase;
use App\Features\Groups\Application\Usecases\ShowGroupUsecase;
use App\Features\Groups\Application\Usecases\StoreGroupUsecase;
use Illuminate\Support\ServiceProvider;

class GroupServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StoreGroupContract::class, StoreGroupUsecase::class);
        $this->app->bind(ShowGroupContract::class, ShowGroupUsecase::class);
        $this->app->bind(DestroyGroupContract::class, DestroyGroupUsecase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
