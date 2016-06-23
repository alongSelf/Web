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

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'MainController@index');
    Route::get('getConfig', 'MainController@getConfig');
    Route::get('categorys', 'MainController@categorys');
    Route::get('indexItem', 'MainController@indexItem');
    Route::get('getCar', 'MainController@getCar');
    Route::get('categoryInfo/{id}', 'MainController@categoryInfo');
    Route::get('itemInfo/{id}', 'MainController@itemInfo');
});
