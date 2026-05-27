<?php


return [
    App\Shared\Infrastructure\Providers\AppServiceProvider::class,
    App\Shared\Infrastructure\Providers\SharedServiceProvider::class,
    App\Features\Users\Infrastructure\Providers\UserServiceProvider::class,
    App\Features\Permissions\Infrastructure\Providers\PermissionServiceProvider::class,
    App\Features\Groups\Infrastructure\Providers\GroupServiceProvider::class,
    App\Features\Categories\Infrastructure\Providers\CategoryServiceProvider::class,
    App\Features\Documents\Infrastructure\Providers\DocumentServiceProvider::class,
    App\Features\AppInfos\Infrastructure\Providers\AppInfoServiceProvider::class,
];
