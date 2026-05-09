<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Outputs;

interface DestroyDocumentOutput
{
    public function onSuccess(): void;
    public function onNotFound(): void;
    public function onUnauthorized(): void;
    public function onFailure(string $error): void;
}
