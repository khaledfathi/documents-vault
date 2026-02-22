<?php

use App\Features\Users\Presentation\API\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


/***** Admin *****/

/***** Users *****/
Route::resource('/users', UserController::class);
/***** Documents *****/

/***** Settings *****/
