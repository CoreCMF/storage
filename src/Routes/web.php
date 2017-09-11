<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
|--------------------------------------------------------------------------
| Admin后台路由设置 routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'OAuth', 'middleware' => 'web', 'namespace' => 'CoreCMF\Storage\Http\Controllers', 'as' => 'OAuth.'], function () {
    Route::get('{service}/callback',        [ 'as' => 'callback', 'uses' => 'AuthController@handleProviderCallback']);
    Route::get('{service}/{redirect?}',     [ 'as' => 'redirect', 'uses' => 'AuthController@redirectToProvider']);
});
