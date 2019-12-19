<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::namespace('Api')->group(function () {
    // token 过期接口返回
    Route::get('unauthorized', 'LoginController@unauthorized')->name('unauthorized');

    // 登录
    Route::post('/login', 'LoginController@login');

    // 注册
    Route::post('/register', 'RegisterController@register');
    Route::get('/test',function(){
        return response()->data(['name'=>'刘勇']);
    });
});


Route::middleware('auth:api')->namespace('Api')->group(function () {
    // 获取当前用户
    Route::get('/current', 'UserController@current');

    Route::apiResource('/user', 'UserController');

    // 退出登录
    Route::post('/logout', 'LoginController@logout');
});
