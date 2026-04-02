<?php
declare (strict_types=1);
namespace  App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\UpdateUserOutput;
use App\Shared\Application\Traits\PresenterTrait;
use Closure;

final class UpdateUserPresenter implements UpdateUserOutput {
    use PresenterTrait;
    private Closure $response;
    public function onSuccess (bool $stauts){
        $this->response = fn()=> response()->json([
            "success"=> true,
            "message"=> "User Updated Successfuly" ,
        ]);
    }
    public function onNotFound ():void{
        $this->response = fn()=> response()->json([
            "success"=> false,
            "message"=> "User is not found" ,
        ]);
    }
    public function handle (){
        return ($this->response)();
    }
}
