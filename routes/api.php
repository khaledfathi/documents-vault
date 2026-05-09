<?php

use app\Features\Categories\Presentation\API\Controllers\CategoryController;
use app\Features\Documents\Presentation\API\Controllers\DocumentContoller;
use App\Features\Groups\Presentation\API\Controllers\GroupController;
use App\Features\Permissions\Presentation\API\Controllers\PermissionController;
use App\Features\Users\Presentation\API\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/***** API Auth *****/
Route::post('/login' , [UserController::class , 'login'])->name('user.login');
/***** ------- *****/

Route::middleware('auth:sanctum')->group(function (){
    //
    Route::resource('/users', UserController::class)->except(['create','edit']);
    //
    Route::resource('/permissions', PermissionController::class)->only(['index']);
    //
    Route::resource('/groups', GroupController::class)->except(['create','edit']);
    //
    Route::resource('/categories', CategoryController::class)->except(['create','edit']);
    //
    Route::prefix('documents/{document}')->group(function () {
        Route::get('files/view/{file}', [DocumentContoller::class, 'viewFile'])->name('documents.files.view')->scopeBindings();
        Route::get('files/download/{file}', [DocumentContoller::class, 'downloadFile'])->name('documents.files.download')->scopeBindings();
    });
    Route::resource('/documents', DocumentContoller::class)->except(['create','edit']);
    //
    Route::get('/logout' , [UserController::class , 'logout'])->name('user.logout');
});

