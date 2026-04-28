<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Outputs;

use App\Shared\Domain\Entities\Document\CategoryEntity;

interface ShowCategoryOutput
{
    public function onSuccess(CategoryEntity $categoryEntity): void;
    public function onNotFound(): void;
    public function onUnauthorized(): void;
    public function onFailure(string $error): void;
}
