<?php
declare (strict_types=1);
namespace App\Features\Users\Presentation\API\Presenters; 

use App\Features\Users\Application\Outputs\PaginateUsersOutput;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use Closure;

final class PaginateUsersPresenter implements PaginateUsersOutput {

    private Closure $response; 
    /**
     * @inheritdoc 
     */
    public function onSuccess (EntitiesWithPagination $entitiesWithPagination){
        $this->response = fn() => response()->json([
            'users' => $entitiesWithPagination->entities , 
            // 'pagination' => $entitiesWithPagination->pagination->toJson(), 
        ]);
    }
    public function onFailure(string $error){
        //log the error , 
        //....
        //action
        $this->response = fn() => response()->json([
            'message' => 'failed to paginate users',
            'error' => $error ,
        ]);
    }
    public function handle (){ 
        return response()->json(['target' => __CLASS__.":".__FUNCTION__]);
    }
}