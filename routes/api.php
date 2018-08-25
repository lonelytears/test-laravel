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

/**
 * 朴邻开放平台路由
 */
Route::group([], function() {
    // 获取城市
    Route::post('getCity', 'PulinApiController@getCity');
    // 获取小区
    Route::post('getCommunity', 'PulinApiController@getCommunity');
    // 获取小区
    Route::post('getCommunityInfo', 'PulinApiController@getCommunityInfo');
    // 获取座栋
    Route::post('getBuilding', 'PulinApiController@getBuilding');
    // 获取房源
    Route::post('getHouse', 'PulinApiController@getHouse');
    // 发送验证码, 限制 IP 十分钟三次请求
    Route::post('sendValidateCode', 'PulinApiController@sendValidateCode')->middleware('throttle:3,10');
});

// 签署
Route::group(['prefix' => 'signature'], function () {
    Route::get('signature', 'SignatureController@user_sign_list');
    Route::get('get_contract_template', 'SignatureController@get_contract_template');
    Route::get('contract_info/{contract_id}', 'SignatureController@contract_info');
    Route::get('check_customer', 'SignatureController@check_customer');
    Route::post('signature/{template}', 'SignatureController@user_sign_list');
//    Route::match(['get', 'post'],'/generate_contract', 'SignatureController@generate_contract');
    Route::get('generate_contract', 'SignatureController@generate_contract');
    Route::post('generate_contract', 'SignatureController@generate_contract')->name('api.SignatureController.generate_contract');
    Route::post('register', 'SignatureController@register')->name('api.SignatureController.register');
    Route::post('upload_template', 'SignatureController@upload_template')->name('api.SignatureController.upload_template');
    Route::post('extsign_auto', 'SignatureController@extsign_auto')->name('api.SignatureController.extsign_auto');
    Route::post('ocr', 'SignatureController@ocr')->name('api.SignatureController.ocr');
});