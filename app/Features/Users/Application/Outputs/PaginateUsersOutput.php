<?php
declare (strict_types=1);
namespace App\Features\Users\Application\Outputs; 

use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;

interface PaginateUsersOutput{
    /**
     * @param EntitiesWithPagination<UserEntity> $entitiesWithPagination
     * @return void
     */
    public function onSuccess (EntitiesWithPagination $entitiesWithPagination);
    public function onFailure(string $error);
}