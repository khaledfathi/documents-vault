<?php

namespace App\Shared\Infrastructure\Providers;

use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\UserRepository;
use App\Shared\Infrastructure\Gateways\UserPermissionGateway;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class SharedServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //Repositories 
        $this->app->bind(UserRepository::class , EloquentUserRepository::class);

        //gateways
        $this->app->bind(PermissionGateway::class , UserPermissionGateway::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
