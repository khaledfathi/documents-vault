<?php

namespace App\Features\Groups\Application\Outputs;

interface UpdateGroupOutput
{
    public function onSuccess(): void;
    public function onUnauthorized(): void;
    public function onNotFound(): void;
    public function onProtectedGroup(): void;
    public function onFailure(string $error): void;
}
