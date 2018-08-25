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

/**
 * 服务器相关路由
 */
Route::group(['prefix' => 'server'], function() {
    // 服务器验证路由
    Route::get('verification', 'ServController@verification');
    // Webhook
    Route::match(['post', 'get'], 'webhook', 'ServController@webhook');
});

/**
 * 招聘相关路由
 */
Route::group(['prefix' => 'recruit'], function () {
    // 招聘页面
    Route::get('submit', 'RecruitController@submit');
    // 招聘提交处理
    Route::post('submit', 'RecruitController@create');
});

/**
 * 乐邦平台路由
 */
Route::group(['prefix' => 'lb'], function() {
    // 二维码回调
    Route::post('callback', 'LBController@callback');
    // 二维码生成
    Route::get('qrcode/{code}', 'LBController@qrcode');
});

// 授权相关路由
Route::group(['prefix' => 'auth'], function() {
    // 用户授权登录
    Route::get('login', 'AuthController@login')->name('login');
    // 微信登录回调
    Route::get('callback', 'AuthController@callback');
    // 退出
    Route::get('logout', 'AuthController@logout');
});

// 看单审核
Route::group(['prefix' => 'look'], function () {
    Route::get('confirm/{code}', 'LookController@confirm');
    Route::post('complete', 'LookController@complete');
});

// 需要登录的路由，未登录跳转到微信授权
Route::group(['middleware' => 'xAuth'], function() {
    // 首页路由
    Route::get('/', 'HomeController@index')->name('home');
    // 用户绑定手机
    Route::get('/user/binding/{auth}', 'UserController@binding')->name('bindingMobile');
    // 用户绑定手机处理
    Route::post('/user/binding/{auth}', 'UserController@bindingMobile');
    // 新增用户信息
    Route::get('/user/create/{auth}', 'UserController@create')->name('createuser');
    // 新增用户或更新（如果手机号存在）路由
    Route::post('/user/create/{auth}', 'UserController@createOrUpdate');

    // 检查手机号，如果不存在跳转到创建信息
    Route::group(['middleware' => 'checkUser'], function() {
        // 用户相关路由
        Route::group(['prefix' => 'user'], function() {
            // 用户信息路由
            Route::get('detail/{user}', 'UserController@index')->name('userinfo');
            // 用户信息修改
            Route::get('edit/{user}', 'UserController@edit')->name('edituser');
            // 用户信息修改提交
            Route::post('edit/{user}', 'UserController@update');
        });

        // 看单相关路由
        Route::group(['prefix' => 'look'], function() {
            // 看单详情页面
            Route::get('detail/{look}', 'LookController@detail');
            // 提交看单页面
            Route::get('submit', 'LookController@submit');
            // 创建看单
            Route::post('submit', 'LookController@create');
        });
    });
});