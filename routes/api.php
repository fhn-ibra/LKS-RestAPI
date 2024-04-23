<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/v1/auth/register', [AuthController::class, 'register']);
Route::post('/v1/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('/v1/auth/logout', [AuthController::class, 'logout']);

    Route::post('/v1/posts', [PostController::class, 'create']);
    Route::delete('/v1/posts/{id}', [PostController::class, 'delete']);
    Route::get('/v1/posts', [PostController::class, 'getAll']);

    Route::get('/v1/users', [UserController::class, 'all']);
    Route::get('/v1/users/{username}', [UserController::class, 'username']);
});
