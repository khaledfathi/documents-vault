<?php

declare(strict_types=1);

namespace App\Features\Groups\Presentation\API\Presenters;

use App\Features\Groups\Application\Outputs\PaginateGroupOutput;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Presentation\API\Traits\PresenterTrait;

final class PaginateGroupPresenter implements PaginateGroupOutput
{
    use PresenterTrait;

    public function onSuccess(EntitiesWithPagination $entitiesWithPagination): void {

    }
    public function handle()
    {
        return ($this->response)();
    }
}
