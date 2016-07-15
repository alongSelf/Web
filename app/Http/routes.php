<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group([], function () {
    Route::get('/', 'ShopController@index');
    Route::get('getConfig', 'ShopController@getConfig');
    Route::get('categorys', 'ShopController@categorys');
    Route::get('indexItem', 'ShopController@indexItem');
    Route::get('loadMoreIndexItem/{page}', 'ShopController@loadMoreIndexItem');
    Route::get('categoryInfo/{id}/{page}', 'ShopController@categoryInfo');
    Route::get('itemInfo/{id}', 'ShopController@itemInfo');
    Route::get('itemEvaluate/{id}/{page}', 'ShopController@itemEvaluate');
    Route::get('search/{param}', 'ShopController@search');

    Route::get('register/{phone}/{psw}', 'UserController@register');
    Route::get('logIn/{phone}/{psw}', 'UserController@logIn');

    //admin
    Route::any('admin/login', 'Admin\LoginController@login');
    Route::get('admin/code', 'Admin\LoginController@code');
});

Route::group(['middleware' => ['user.login']], function () {
    Route::get('logOut', 'UserController@logOut');
    Route::get('getUserBase', 'UserController@getUserBase');
    Route::get('getUserInfo', 'UserController@getUserInfo');
    Route::get('bindAccount/{phone}/{psw}', 'UserController@bindAccount');
    Route::get('changePsw/{oldpsw}/{newpsw}', 'UserController@changePsw');
    Route::get('changeUserInfo/{info}', 'UserController@changeUserInfo');
    Route::get('saveAddr/{addr}', 'UserController@saveAddr');
    Route::get('getAddr', 'UserController@getAddr');
    Route::get('delAddr/{id}', 'UserController@delAddr');

    Route::get('agent/{name}/{phone}', 'UserController@agent');
    Route::get('agentShow', 'UserController@agentShow');
    Route::get('canShowQRC', 'UserController@canShowQRC');
    Route::get('loadIncomeData/{page}', 'UserController@loadIncomeData');
    Route::get('loadCashData/{page}', 'UserController@loadCashData');
    Route::get('cash/{money}', 'UserController@cash');
    Route::get('showLevel/{followerid}', 'UserController@showLevel');
    Route::get('getArea1', 'UserController@getArea1');
    Route::get('getChildArea/{parentNo}', 'UserController@getChildArea');
});

Route::group(['middleware' => ['admin.login'], 'prefix'=>'admin', 'namespace'=>'Admin'], function () {
    Route::any('upload', 'CommonController@upload');

    Route::get('index', 'IndexController@index');
    Route::get('quit', 'LoginController@quit');
    Route::any('pass', 'IndexController@pass');
    Route::get('info', 'IndexController@info');

    Route::post('category/changeOrder', 'CategoryController@changeOrder');
    Route::resource('category', 'CategoryController');

    Route::any('shop/searchbycate/{cate_id}', 'ShopController@searchbycate');
    Route::any('shop/searchbyname/{name}', 'ShopController@searchbyname');
    Route::resource('shop', 'ShopController');

    Route::get('other/config', 'OtherController@configIndex');
    Route::any('other/changeTitle', 'OtherController@changeTitle');
    Route::get('other/notice', 'OtherController@noticeIndex');
    Route::any('other/changeNotice', 'OtherController@changeNotice');
    Route::get('other/evaluates', 'OtherController@evaluatesIndex');
    Route::any('other/delEvaluates', 'OtherController@delEvaluates');
    Route::any('other/searchEvaluates/{ev_id}', 'OtherController@searchEvaluates');
    Route::any('other/changeAgent', 'OtherController@changeAgent');
    Route::any('other/changeSpread', 'OtherController@changeSpread');
    Route::any('other/changeOpenSpread', 'OtherController@changeOpenSpread');
    Route::any('other/changeCash', 'OtherController@changeCash');
    Route::any('other/changeCommission1', 'OtherController@changeCommission1');
    Route::any('other/changeCommission2', 'OtherController@changeCommission2');
    Route::any('other/changeCommission3', 'OtherController@changeCommission3');
    Route::any('other/contactus', 'OtherController@contactus');
    Route::any('other/changeContactus', 'OtherController@changeContactus');

    Route::get('user/index', 'UserController@index');
    Route::get('order/index', 'OrderController@index');
});
