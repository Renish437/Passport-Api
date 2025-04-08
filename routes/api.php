<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');
Route::post('/register', [App\Http\Controllers\Api\ApiController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\ApiController::class, 'login']);
Route::middleware('auth:api')->group(function(){

    Route::get('/profile', [App\Http\Controllers\Api\ApiController::class, 'profile']);
    Route::get('/refresh-token', [App\Http\Controllers\Api\ApiController::class, 'refreshToken']);
    Route::delete('/logout', [App\Http\Controllers\Api\ApiController::class, 'logout']);
});