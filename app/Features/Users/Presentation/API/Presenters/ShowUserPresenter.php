<?php
declare (strict_types=1);
namespace  App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\ShowUserOutput;
use App\Shared\Domain\Entities\User\UserEntity;
use Closure;

final class ShowUserPresenter implements ShowUserOutput {

    private Closure $response ; 
    public function onSuccess (UserEntity $userEntity):void{
        dd('sueccess', $userEntity);
    }
    public function onNotFound ():void{
        dd('notfound');
    }
    public function onUnauthorized():void{
        dd('unauthorized');
    }
    public function onFailure (string $error):void{
        dd('failure' , $error);
    }
    public function handle(){
        return ($this->response)();

    }
}
