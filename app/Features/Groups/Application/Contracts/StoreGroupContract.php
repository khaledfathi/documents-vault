<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Contracts;

use App\Features\Groups\Application\Outputs\StoreGroupOutput;
use App\Shared\Domain\Entities\Group\GroupEntity;

interface StoreGroupContract
{
    public function execute(GroupEntity $groupEntity, StoreGroupOutput $presenter);
}
