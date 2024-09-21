<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Route::post('logout', [UserController::class, 'logout'])->middleware('auth:api');
// Route::get('profile', [UserController::class, 'profile'])->middleware('auth:api');
// Route::post('refresh-token', [UserController::class, 'refreshToken'])->middleware('auth:api');

