<?php

use App\Http\Controllers\Api\V1\NoteController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\Auth\UserAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);
    Route::post('refresh-token', [UserAuthController::class, 'refreshToken']);
    Route::middleware('auth:api')->group(function() {
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::get('user', [UserController::class, 'user']);
        Route::apiResource('notes', NoteController::class);
    });
});
