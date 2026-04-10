<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Outputs;

interface DestroyGroupOutput
{
    public function onSuccess(): void;
    public function onUnauthorized(): void;
    public function onNotfound(): void;
    public function onDefaultGroups(): void;
    public function onFailure(string $error): void;
}
