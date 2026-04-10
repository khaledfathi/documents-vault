<?php

declare(strict_types=1);

namespace App\Features\Users\Application\Outputs;

use App\Shared\Application\Traits\PresenterTrait;

interface DestroyUserOutput
{
    public function onSuccess(): void;
    public function onNotFound(): void;
    public function onUnauthorized(): void;
    public function onAdminUser(): void;
    public function onFailure(string $error): void;
}
