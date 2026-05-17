<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Outputs;

use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Domain\Entities\Group\GroupEntity;

interface PaginateGroupOutput
{

    /**
     * @param EntitiesWithPagination<GroupEntity> $entitiesWithPagination
     * @return void
     */
    public function onSuccess(EntitiesWithPagination $entitiesWithPagination): void;
    public function onUnauthorized(): void;
    public function onFailure(string $error): void;
}
