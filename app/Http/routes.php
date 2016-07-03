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
    Route::get('/', 'MainController@index');
    Route::get('getConfig', 'MainController@getConfig');
    Route::get('categorys', 'MainController@categorys');
    Route::get('indexItem', 'MainController@indexItem');
    Route::get('loadMoreIndexItem/{page}', 'MainController@loadMoreIndexItem');
    Route::get('categoryInfo/{id}/{page}', 'MainController@categoryInfo');
    Route::get('itemInfo/{id}', 'MainController@itemInfo');
    Route::get('search/{param}', 'MainController@search');

    //admin
    Route::any('admin/login', 'Admin\LoginController@login');
    Route::get('admin/code', 'Admin\LoginController@code');
});

Route::group(['middleware' => ['admin.login'], 'prefix'=>'admin', 'namespace'=>'Admin'], function () {
    Route::any('upload', 'CommonController@upload');

    Route::get('index', 'IndexController@index');
    Route::get('quit', 'LoginController@quit');
    Route::any('pass', 'IndexController@pass');
    Route::get('info', 'IndexController@info');

    Route::post('category/changeOrder', 'CategoryController@changeOrder');
    Route::resource('category', 'CategoryController');

    Route::resource('shop', 'ShopController');
});
