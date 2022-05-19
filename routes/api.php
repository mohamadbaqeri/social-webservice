<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
Route::middleware('auth:api', 'verified')->get('/user', function (Request $request) {
    return $request->user();
});

// User Authentication *********************************************

Route::prefix('/v1/auth')->group(callback: function () {
    Route::post('/register', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'login']);//->middleware('throttle:login');
    Route::post('/logout', [\App\Http\Controllers\Api\V1\Auth\AuthController::class, 'logout'])->middleware('auth:api');
});


// User Panel *******************************************************

Route::prefix('/v1/panel')->group(function () {
    // user update ******************
    Route::post('/user/update', [\App\Http\Controllers\Api\V1\Panel\UserController::class, 'update'])
        ->middleware('auth:api');
    Route::post('/user/skill', [\App\Http\Controllers\Api\V1\Panel\UserController::class, 'addSkill'])
        ->middleware('auth:api');
    Route::get('/user/info', [\App\Http\Controllers\Api\V1\Panel\UserController::class, 'information'])
        ->middleware('auth:api');
    Route::get('/guest/{id}', [\App\Http\Controllers\Api\V1\Panel\UserController::class, 'guest']);

    // posts ************************
    Route::post('/post/create', [\App\Http\Controllers\Api\V1\Panel\PostController::class, 'create'])
        ->middleware('auth:api');
    Route::put('/post/{post_id}/update', [\App\Http\Controllers\Api\V1\Panel\PostController::class, 'update'])
        ->middleware('auth:api');
    Route::delete('/post/{post_id}/delete', [\App\Http\Controllers\Api\V1\Panel\PostController::class, 'delete'])
        ->middleware('auth:api');
    Route::get('/post/search', [\App\Http\Controllers\Api\V1\Panel\PostController::class, 'searchPost']);

    // comments ***************
    Route::post('/post/{post_id}/comments/create', [\App\Http\Controllers\Api\V1\Panel\CommentController::class, 'create'])
        ->middleware('auth:api');
    Route::put('/post/{post_id}/comments/update', [\App\Http\Controllers\Api\V1\Panel\CommentController::class, 'update'])
        ->middleware('auth:api');
    Route::delete('/post/{post_id}/comments/delete', [\App\Http\Controllers\Api\V1\Panel\CommentController::class, 'delete'])
        ->middleware('auth:api');
    Route::get('/post/{post_id}/comments', [\App\Http\Controllers\Api\V1\Panel\CommentController::class, 'show'])
        ->middleware('auth:api');

    //  likes ****************
    Route::post('/post/{post_id}/like', [\App\Http\Controllers\Api\V1\Panel\LikeController::class, 'store'])
        ->middleware('auth:api');

    // show single post ****************************
    Route::get('/post/{post_id}/single-post', [\App\Http\Controllers\Api\V1\Panel\SinglePostController::class, 'singlePost'])
        ->middleware('auth:api');
});


// Admin Panel ************************************************************************************

Route::prefix('/v1/adminPanel')->middleware('auth:api')->group(function () {
    Route::post('/admin-login', [\App\Http\Controllers\Api\V1\AdminPanel\AdminLoginController::class, 'adminLogin']);
    //  admin login instead user
    Route::post('/admin/{user_id}/login', [\App\Http\Controllers\Api\V1\AdminPanel\AdminLoginController::class, 'loginUser']);

    Route::patch('/admin/posts/verify', [\App\Http\Controllers\Api\V1\AdminPanel\AdminPostVerify::class, 'verify']);
    Route::patch('/admin/posts/reject', [\App\Http\Controllers\Api\V1\AdminPanel\AdminPostReject::class, 'reject']);

    Route::get('/admin/show-users', [\App\Http\Controllers\Api\V1\AdminPanel\ShowDetailController::class, 'showUsers']);
    Route::get('/admin/{user_id}/show-posts', [\App\Http\Controllers\Api\V1\AdminPanel\ShowDetailController::class, 'showPosts']);
});
