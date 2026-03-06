<?php

namespace App\Features\Users\Presentation\API\Controllers;

use App\Features\Users\Application\Contracts\PaginateUsersContract;
use App\Features\Users\Application\Contracts\ShowUserContract;
use App\Features\Users\Presentation\API\Presenters\PaginateUsersPresenter;
use App\Features\Users\Presentation\API\Presenters\ShowUserPresenter;
use App\Http\Controllers\Controller;
use App\Shared\Infrastructure\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        private readonly PaginateUsersContract $paginateUsersUsecase, 
        private readonly ShowUserContract  $showUserUsecase, 
    ) { }
    public function generateToken (Request $request){
        $user = User::where('email', $request->email)->first();
        if(! $user || ! Hash::check($request->password , $user->password) ) {
            return response()->json(['error' => 'invalid user name or password']);
        }
        return response()->json(['token' => $user->createToken($request->token_name ?? 'token_name')->plainTextToken]);
    }
    public function index (Request $request){
        return response()->json(['target' => __CLASS__.":".__FUNCTION__]);
    }
    public function show (Request $request , string $userId){
        $currentUserId = $request->user()->id ?? 0 ;
        $presenter = new ShowUserPresenter();
        $this->showUserUsecase->execute($currentUserId , $userId , $presenter);
        return $presenter->handle(); 
    }
    public function create (){
        return response()->json(['target' => __CLASS__.":".__FUNCTION__]);
    }
    public function store (){
        return response()->json(['target' => __CLASS__.":".__FUNCTION__]);
    }
    public function edit (){
        return response()->json(['target' => __CLASS__.":".__FUNCTION__]);
    }
    public function update (){
        return response()->json(['target' => __CLASS__.":".__FUNCTION__]);
    }
    public function delete (){
        return response()->json(['target' => __CLASS__.":".__FUNCTION__]);
    }
}
