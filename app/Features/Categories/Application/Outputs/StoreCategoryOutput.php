<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Outputs;

use App\Shared\Domain\Entities\Document\CategoryEntity;

interface StoreCategoryOutput
{
    public function onSuccess(CategoryEntity $categoryEntity): void;
    public function onFailure(string $error): void;
    public function onUnauthorized(): void;
}
