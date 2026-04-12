<?php

declare(strict_types=1);

namespace App\Features\Groups\Application\Usecases;

use App\Features\Groups\Application\Contracts\PaginateGroupContract;
use App\Features\Groups\Application\Outputs\PaginateGroupOutput;

final class PaginateGroupUsecase implements PaginateGroupContract
{
    public function execute(PaginateGroupOutput $presenter, int $perPage = 10){

    }
}
