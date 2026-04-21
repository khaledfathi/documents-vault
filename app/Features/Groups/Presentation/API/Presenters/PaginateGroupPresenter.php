<?php

declare(strict_types=1);

namespace App\Features\Groups\Presentation\API\Presenters;

use App\Features\Groups\Application\Outputs\PaginateGroupOutput;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class PaginateGroupPresenter implements PaginateGroupOutput
{
    use PresenterTrait;

    public function onSuccess(EntitiesWithPagination $entitiesWithPagination): void {
        $this->response = fn() => response()->json([
            'success' => true,
            'data' => [
                'pagination' => $entitiesWithPagination->pagination,
                'paginationControl' => [
                    'pageCount' => $entitiesWithPagination->pagination->getPageCounts(),
                    'currentPageURL' => $entitiesWithPagination->pagination->getCurrentPageURL(),
                    'nextPageURL' => $entitiesWithPagination->pagination->getNextPageURL(),
                    'previousePageURL' => $entitiesWithPagination->pagination->getPreviousPageURL(),
                    'links' => $entitiesWithPagination->pagination->getLinks(),
                ],
                'groups' => array_map(fn($entity) => $entity->toArray(), $entitiesWithPagination->entities),
            ],
        ], Response::HTTP_OK);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
