<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Outputs;

use App\Shared\Domain\Entities\User\GroupEntity;

interface StoreGroupOutput
{
    public function onSuccess(GroupEntity $groupEntity): void;
    public function onUnauthorized(): void;
    public function onFailure(string $error): void;
}
