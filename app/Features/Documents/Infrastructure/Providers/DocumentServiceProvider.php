<?php

namespace App\Features\Documents\Infrastructure\Providers;

use App\Features\Documents\Application\Usecases\ShowDocumentFileUsecase;
use App\Features\Documents\Application\Contracts\DestroyDocumentContract;
use App\Features\Documents\Application\Contracts\PaginateDocumentContract;
use App\Features\Documents\Application\Contracts\ShowDocumentContract;
use App\Features\Documents\Application\Contracts\ShowDocumentFileContract;
use App\Features\Documents\Application\Contracts\StoreDocumentContract;
use App\Features\Documents\Application\Contracts\UpdateDocumentContract;
use App\Features\Documents\Application\Usecases\DestroyDocumentUsecase;
use App\Features\Documents\Application\Usecases\PaginateDocumentUsecase;
use App\Features\Documents\Application\Usecases\ShowDocumentUsecase;
use App\Features\Documents\Application\Usecases\StoreDocumentUsecase;
use App\Features\Documents\Application\Usecases\UpdateDocumentUsecase;
use Illuminate\Support\ServiceProvider;

class DocumentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //Document usecases
        $this->app->bind(StoreDocumentContract::class, StoreDocumentUsecase::class);
        $this->app->bind(DestroyDocumentContract::class, DestroyDocumentUsecase::class);
        $this->app->bind(ShowDocumentContract::class, ShowDocumentUsecase::class);
        $this->app->bind(PaginateDocumentContract::class, PaginateDocumentUsecase::class);
        $this->app->bind(UpdateDocumentContract::class, UpdateDocumentUsecase::class);
        //File usecases
        $this->app->bind(ShowDocumentFileContract::class, ShowDocumentFileUsecase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
