<?php
declare (strict_types=1);
namespace App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\PaginateUsersOutput;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Closure;
use Illuminate\Http\Response;

final class PaginateUsersPresenter implements PaginateUsersOutput {

    use PresenterTrait;
    private Closure $response;
    /**
     * @inheritdoc
     */
    public function onSuccess (EntitiesWithPagination $entitiesWithPagination){
        $this->response = fn() => response()->json([
            'success'=> true,
            'data' =>[
                'pagination' => $entitiesWithPagination->pagination,
                'paginationControl' => [
                    'pageCount' => $entitiesWithPagination->pagination->getPageCounts(),
                    'currentPageURL' => $entitiesWithPagination->pagination->getCurrentPageURL(),
                    'nextPageURL' => $entitiesWithPagination->pagination->getNextPageURL(),
                    'previousePageURL' => $entitiesWithPagination->pagination->getPreviousPageURL(),
                    'links' => $entitiesWithPagination->pagination->getLinks(),

                ],
                'users' => array_map( fn($entity)=> $entity->toArray() , $entitiesWithPagination->entities)
            ]
        ] , Response::HTTP_OK);
    }
    public function handle (){
        return ($this->response)();
    }
}
