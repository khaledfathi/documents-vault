<?php

namespace App\Shared\Infrastructure\Providers;

use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Application\Contracts\Storage\StorageContract;
use App\Shared\Application\Contracts\Storage\StorageDirContract;
use App\Shared\Application\Contracts\Utilities\PasswordHasherContract;
use App\Shared\Application\Contracts\Utilities\TokenGeneratorContract;
use App\Shared\Application\Utilities\UtilityStorageDir;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\CategoryRepository;
use App\Shared\Domain\Repositories\DocumentCategoryRepository;
use App\Shared\Domain\Repositories\DocumentRepository;
use App\Shared\Domain\Repositories\FileRepository;
use App\Shared\Domain\Repositories\GroupRepository;
use App\Shared\Domain\Repositories\PermissionRepository;
use App\Shared\Domain\Repositories\UserRepository;
use App\Shared\Infrastructure\Gateways\UserPermissionGateway;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentCategoryRepository;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentDocumentCategoryRepository;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentDocumentRepository;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentFileRepository;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentGroupRepository;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentPermissionRepository;
use App\Shared\Infrastructure\Repositories\Eloquent\EloquentUserRepository;
use App\Shared\Infrastructure\Security\LaravelCurrentUser;
use App\Shared\Infrastructure\Storage\LaravelStorage;
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
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(GroupRepository::class, EloquentGroupRepository::class);
        $this->app->bind(PermissionRepository::class, EloquentPermissionRepository::class);
        $this->app->bind(CategoryRepository::class , EloquentCategoryRepository::class);
        $this->app->bind(DocumentRepository::class , EloquentDocumentRepository::class);
        $this->app->bind(DocumentCategoryRepository::class , EloquentDocumentCategoryRepository::class);
        $this->app->bind(FileRepository::class , EloquentFileRepository::class);

        //gateways
        $this->app->bind(PermissionGateway::class, UserPermissionGateway::class);

        //utilities
        $this->app->bind(TokenGeneratorContract::class, TokenGeneratorUtility::class);
        $this->app->bind(PasswordHasherContract::class, PasswordHasherUtility::class);
        $this->app->bind(StorageDirContract::class , UtilityStorageDir::class);
        $this->app->bind(StorageContract::class , LaravelStorage::class);

        //Security
        $this->app->bind(CurrentUserContract::class, LaravelCurrentUser::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
