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

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');



Route::group(['prefix'=>'backend'],function(){

//    Route::any('/','App\Controllers\Admin\IndexController@index');

    Route::resource('home', 'backend\HomeController');
    Route::resource('cate','backend\CateController');
    Route::resource('content','backend\ContentController');

    Route::controllers([
        'auth' => 'backend\AuthController',
        'password' => 'backend\PasswordController',
    ]);

});