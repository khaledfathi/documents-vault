<?php

namespace App\Shared\Infrastructure\Providers;

use App\Features\Users\Application\Contracts\CreateUserContract;
use App\Features\Users\Application\Contracts\DestroyUserContract;
use App\Features\Users\Application\Contracts\EditUserContract;
use App\Features\Users\Application\Contracts\PaginateUsersContract;
use App\Features\Users\Application\Contracts\ShowUserContract;
use App\Features\Users\Application\Contracts\StoreUserContract;
use App\Features\Users\Application\Contracts\UpdateUserContract;
use App\Features\Users\Application\Usecases\CreateUserUsecase;
use App\Features\Users\Application\Usecases\DestroyUserContractUsecase;
use App\Features\Users\Application\Usecases\EditUserContractUsecase;
use App\Features\Users\Application\Usecases\PaginateUsersUsecase;
use App\Features\Users\Application\Usecases\ShowUserUsecase;
use App\Features\Users\Application\Usecases\StoreUserUsecase;
use App\Features\Users\Application\Usecases\UpdateUserUsecase;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CreateUserContract::class , CreateUserUsecase::class);
        $this->app->bind(DestroyUserContract::class , DestroyUserContractUsecase::class);
        $this->app->bind(EditUserContract::class , EditUserContractUsecase::class);
        $this->app->bind(PaginateUsersContract::class , PaginateUsersUsecase::class);
        $this->app->bind(StoreUserContract::class , StoreUserUsecase::class);
        $this->app->bind(ShowUserContract::class, ShowUserUsecase::class);
        $this->app->bind(UpdateUserContract::class , UpdateUserUsecase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
