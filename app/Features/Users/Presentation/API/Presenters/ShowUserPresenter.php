<?php
declare (strict_types=1);
namespace  App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\ShowUserOutput;
use App\Shared\Domain\Entities\User\UserEntity;
use Closure;

final class ShowUserPresenter implements ShowUserOutput {

    private Closure $response ; 
    public function onSuccess (UserEntity $userEntity):void{
        $this->response = fn()=> response()->json([
            "success"=> true,
            "message"=> "" ,
            'data' => $userEntity->toArray()
        ]);
    }
    public function onNotFound ():void{
        $this->response = fn()=> response()->json([
            "success"=> false,
            "message"=> "User is not found" ,
            'data' => null,  
        ]);
    }
    public function onUnauthorized():void{
        $this->response = fn()=> response()->json([
            "success"=> false,
            "message"=> "current user is Unauthorized to do this action" ,
            'data' => null,  
        ]);
    }
    public function onFailure (string $error):void{
        $data = [
            "success"=> false,
            "message"=> "internal server error" ,
            'data' => null,  
        ];
        if(getenv('APP_DEBUG'))  $data['error'] = $error;
        $this->response = fn()=> response()->json($data);
    }
    public function handle(){
        return ($this->response)();

    }
}
