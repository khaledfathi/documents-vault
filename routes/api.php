<?php

use App\Features\Users\Presentation\API\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/***** API Token *****/
Route::post('/tokens/create' , [UserController::class , 'generateToken'])->name('tokens.create');

/***** Users *****/
Route::resource('/users', UserController::class)->middleware('auth:sanctum');
/***** Documents *****/

/***** Settings *****/
