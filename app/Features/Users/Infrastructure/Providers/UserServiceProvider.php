<?php

namespace App\Features\Users\Infrastructure\Providers;

use App\Features\Users\Application\Contracts\DestroyTokenContract;
use App\Features\Users\Application\Contracts\DestroyUserContract;
use App\Features\Users\Application\Contracts\GenerateTokenContract;
use App\Features\Users\Application\Contracts\PaginateUsersContract;
use App\Features\Users\Application\Contracts\ResetAdminUserContract;
use App\Features\Users\Application\Contracts\ShowUserContract;
use App\Features\Users\Application\Contracts\StoreUserContract;
use App\Features\Users\Application\Contracts\UpdateUserContract;
use App\Features\Users\Application\Usecases\DestroyTokenUsecase;
use App\Features\Users\Application\Usecases\DestroyUserUsecase;
use App\Features\Users\Application\Usecases\GenerateTokenUsecase;
use App\Features\Users\Application\Usecases\PaginateUsersUsecase;
use App\Features\Users\Application\Usecases\ResetAdminUserUsecase;
use App\Features\Users\Application\Usecases\ShowUserUsecase;
use App\Features\Users\Application\Usecases\StoreUserUsecase;
use App\Features\Users\Application\Usecases\UpdateUserUsecase;
use App\Features\Users\Presentation\Console\Commands\ResetAdmin;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaginateUsersContract::class , PaginateUsersUsecase::class);
        $this->app->bind(StoreUserContract::class , StoreUserUsecase::class);
        $this->app->bind(ShowUserContract::class, ShowUserUsecase::class);
        $this->app->bind(UpdateUserContract::class , UpdateUserUsecase::class);
        $this->app->bind(DestroyUserContract::class , DestroyUserUsecase::class);
        $this->app->bind(ResetAdminUserContract::class , ResetAdminUserUsecase::class);
        //api tokens
        $this->app->bind(GenerateTokenContract::class , GenerateTokenUsecase::class);
        $this->app->bind(DestroyTokenContract::class , DestroyTokenUsecase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if($this->app->runningInConsole()){
            $this->commands([
                ResetAdmin::class,
            ]);
        }
    }
}
