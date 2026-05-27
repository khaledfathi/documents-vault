<?php

namespace App\Features\AppInfos\Infrastructure\Providers;

use App\Features\AppInfos\Application\Contracts\ShowAllAppInfoContract;
use App\Features\AppInfos\Application\Usecases\ShowAllAppInfoUsecase;
use Illuminate\Support\ServiceProvider;

class AppInfoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ShowAllAppInfoContract::class, ShowAllAppInfoUsecase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
