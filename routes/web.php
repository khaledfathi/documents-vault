<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return response()->json([
        'success' => false,
        'message' => 'Unauthenticated. Please provide a valid API token.',
    ], 401);
})->name('login'); // This name is what Laravel is looking for