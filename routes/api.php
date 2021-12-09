<?php

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

Route::group([
    'middleware' => ['api'],
    'prefix' => 'v1',
    'namespace' => 'App\Http\Controllers\Api\V1'
], function ($router) {
    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('logout', 'AuthController@logout')->name('logout');
        Route::post('refresh', 'AuthController@refresh')->name('refresh');
        Route::post('me', 'AuthController@me')->name('me');
        Route::post('register', 'AuthController@register')->name('register');
    });

    Route::apiResource('communities', 'CommunityController')->middleware('auth.jwt');

    Route::apiResource('communities.threads', 'CommunityThreadController')
        ->only('index', 'store')
        ->middleware('auth.jwt');

    Route::apiResource('threads', 'ThreadController')
        ->except('index')
        ->middleware('auth.jwt');

    Route::apiResource('threads.posts', 'ThreadPostController')
        ->only('index', 'store')
        ->middleware('auth.jwt');

    Route::apiResource('posts', 'PostController')
        ->except('index')
        ->middleware('auth.jwt');
});