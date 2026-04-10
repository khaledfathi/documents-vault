<?php

declare(strict_types=1);

namespace App\Features\Users\Application\Outputs;


interface DestroyUserOutput
{
    public function onSuccess(): void;
    public function onNotFound(): void;
    public function onUnauthorized(): void;
    public function onRoorUser(): void;
    public function onFailure(string $error): void;
}
