<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\IndexController;
use App\Http\Controllers\Api\v1\PostController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
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
    Route::get('search/user', [IndexController::class, 'searchUser']);
    Route::get('user/{user}/posts', [IndexController::class, 'userPost']);
    Route::get('search/posts/popular', [IndexController::class, 'searchByLike']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('post/{post}/like', [IndexController::class, 'likePost']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('posts', PostController::class)->only(['store', 'update', 'destory']);
});
