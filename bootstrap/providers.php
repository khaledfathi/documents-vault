<?php

return [
    App\Shared\Infrastructure\Providers\AppServiceProvider::class,
    App\Shared\Infrastructure\Providers\SharedServiceProvider::class,
    App\Features\Users\Infrastructure\Providers\UserServiceProvider::class,
    App\Features\Permissions\Infrastructure\Providers\PermissionServiceProvider::class,
    App\Features\Groups\Infrastructure\Providers\GroupServiceProvider::class
];
