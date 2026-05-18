<?php

namespace App\Features\Groups\Application\Outputs;

interface UpdateGroupOutput
{
    public function onSuccess(): void;
    public function onUnauthorized(): void;
    public function onNotFound(): void;
    public function onAdminGroup(): void;
    public function onFailure(string $error): void;
}
