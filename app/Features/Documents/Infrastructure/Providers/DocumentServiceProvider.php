<?php

namespace App\Features\Documents\Infrastructure\Providers;

use App\Features\Documents\Application\Contracts\StoreDocumentContract;
use App\Features\Documents\Application\Usecases\StoreDocumentUsecase;
use Illuminate\Support\ServiceProvider;

class DocumentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StoreDocumentContract::class, StoreDocumentUsecase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
