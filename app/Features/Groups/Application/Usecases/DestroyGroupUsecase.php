<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Usecases;

use App\Features\Groups\Application\Contracts\DestroyGroupContract;
use App\Features\Groups\Application\Outputs\DestroyGroupOutput;

final class DestroyGroupUsecase implements DestroyGroupContract
{
    public function execute(int $groupId, DestroyGroupOutput $presenter): void{}
}
