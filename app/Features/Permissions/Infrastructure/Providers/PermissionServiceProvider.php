<?php

namespace App\Features\Permissions\Infrastructure\Providers;

use App\Features\Permissions\Application\Contracts\ListPermissionsContract;
use App\Features\Permissions\Application\Usecases\ListPermissionsUsecase;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ListPermissionsContract::class, ListPermissionsUsecase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
