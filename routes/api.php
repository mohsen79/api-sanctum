<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\BookmarkController;
use App\Http\Controllers\Api\v1\CommentController;
use App\Http\Controllers\Api\v1\IndexController;
use App\Http\Controllers\Api\v1\PostController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\TagController;
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
    Route::get('user/{user}/following', [IndexController::class, 'following']);
    Route::get('user/{user}/followers', [IndexController::class, 'followers']);
    Route::get('user/{user}/comments', [CommentController::class, 'userComments']);
    Route::get('post/{post}/comments', [CommentController::class, 'postComments']);
    Route::get('tag/{tag}/posts', [TagController::class, 'tagPosts']);
    Route::get('post/{post}/tags', [TagController::class, 'postTags']);
    Route::get('search/posts/tags', [TagController::class, 'searchPostByTags']);
    Route::get('tags', [TagController::class, 'tags']);
    Route::get('popular/tags', [TagController::class, 'popularTags']);
    Route::get('post/{post}/liked/users', [IndexController::class, 'usersLikedPost']);
    Route::get('post/{post}/disliked/users', [IndexController::class, 'usersDisLikedPost']);
    Route::get('comment/{comment}/liked/users', [CommentController::class, 'usersLikedComment']);
    Route::get('comment/{comment}/disliked/users', [CommentController::class, 'usersDisLikedComment']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
});

//protected routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('user/search/bookmarks', [BookmarkController::class, 'searchBookmarks']);
    Route::post('post/{post}/bookmark', [BookmarkController::class, 'bookmark']);
    Route::get('user/{user}/bookmarks', [BookmarkController::class, 'bookmarks']);
    Route::get('post/{post}/bookmarked', [BookmarkController::class, 'bookmarked']);
    Route::post('post/{post}/like', [IndexController::class, 'likePost']);
    Route::post('post/{post}/dislike', [IndexController::class, 'DisLikePost']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('follow/user/{user}', [IndexController::class, 'follow']);
    Route::post('post/{post}/comment', [CommentController::class, 'comment']);
    Route::post('comment/{comment}/like', [CommentController::class, 'likeComment']);
    Route::post('comment/{comment}/dislike', [CommentController::class, 'Dislikecomment']);
    Route::post('tag/create', [TagController::class, 'createTag']);
    Route::delete('tag/{tag}/delete/post', [TagController::class, 'deletePostByTag']);
    Route::apiResource('users', UserController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('posts', PostController::class)->only(['store', 'update', 'destroy']);
});
