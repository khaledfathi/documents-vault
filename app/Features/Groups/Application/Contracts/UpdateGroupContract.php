<?php

namespace App\Features\Groups\Application\Contracts;

use App\Features\Groups\Application\Outputs\UpdateGroupOutput;
use App\Shared\Domain\Entities\Group\GroupEntity;

interface UpdateGroupContract
{
    public function execute(GroupEntity $groupEntity, UpdateGroupOutput $presenter): void;
}
