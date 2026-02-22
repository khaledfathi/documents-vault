<?php

namespace App\Shared\Infrastructure\Providers;

use App\Shared\Domain\Repositories\UserRepository;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class SharedServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EloquentUserRepository::class , UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
