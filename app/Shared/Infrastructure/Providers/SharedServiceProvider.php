<?php

namespace App\Shared\Infrastructure\Providers;

use App\Shared\Application\Contracts\CurrentUserContract;
use App\Shared\Application\Contracts\PasswordHasherContract;
use App\Shared\Application\Contracts\TokenGeneratorContract;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\GroupRepository;
use App\Shared\Domain\Repositories\UserRepository;
use App\Shared\Infrastructure\Gateways\UserPermissionGateway;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentGroupRepository;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentUserRepository;
use App\Shared\Infrastructure\Security\LaravelCurrentUser;
use App\Shared\Infrastructure\Utilities\PasswordHasherUtility;
use App\Shared\Infrastructure\Utilities\TokenGeneratorUtility;
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
        $this->app->bind(GroupRepository::class, EloquentGroupRepository::class);

        //gateways
        $this->app->bind(PermissionGateway::class , UserPermissionGateway::class);

        //utilities
        $this->app->bind(TokenGeneratorContract::class , TokenGeneratorUtility::class);
        $this->app->bind(PasswordHasherContract::class , PasswordHasherUtility::class);

        //Security
        $this->app->bind(CurrentUserContract::class , LaravelCurrentUser::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
