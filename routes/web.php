<?php

use App\Shared\Infrastructure\Constants\Messages;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return response()->json([
        'success' => false,
        'message' => Messages::UNAUTHENTICATED,
    ], 401);
})->name('login'); // This name is what Laravel is looking for
