<?php

namespace App\Shared\Infrastructure\Providers;

use App\Features\Users\Application\Contracts\StoreUserContract;
use App\Features\Users\Application\Usecases\StoreUserUsecase;
use App\Shared\Domain\Repositories\UserRepository;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StoreUserContract::class , StoreUserUsecase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
