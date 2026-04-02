<?php
declare (strict_types=1);
namespace  App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\ShowUserOutput;
use App\Shared\Application\Enums\Messages;
use App\Shared\Application\Traits\PresenterTrait;
use App\Shared\Domain\Entities\User\UserEntity;
use Closure;

final class ShowUserPresenter implements ShowUserOutput {
    use PresenterTrait;
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
        ]);
    }
    public function onFailure (string $error):void{
        $data = [
            "success"=> false,
            "message"=> Messages::SERVER_ERROR ,
        ];
        $this->onDebug($data, $error);
        $this->response = fn()=> response()->json($data);
    }
    public function handle(){
        return ($this->response)();

    }
}
