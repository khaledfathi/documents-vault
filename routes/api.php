<?php

use App\Features\Users\Presentation\API\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/***** API Auth *****/
Route::post('/login' , [UserController::class , 'login'])->name('user.login');

/***** Users *****/
Route::middleware('auth:sanctum')->group(function (){
    Route::resource('/users', UserController::class)->except(['create','edit']);
    Route::get('/logout' , [UserController::class , 'logout'])->name('user.logout');
});

/***** Documents *****/

/***** Settings *****/
