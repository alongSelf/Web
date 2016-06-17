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
    Route::get('/', 'IndexController@index');
    Route::get('home', 'HomeController@index');
    Route::get('user', 'UserController@index');
    Route::get('car', 'CarController@index');
    Route::get('category/{id}', 'ItemController@index');
    Route::get('item/{id}', 'ItemController@item');
});
