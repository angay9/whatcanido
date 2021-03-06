<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// die(bcrypt('password'));

Route::get('/', function () {
    return redirect()->to('/events');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    // Route::get('/home', 'HomeController@index');

    /**
     * Events
     */
    Route::group(['prefix' => 'events'], function () {
        Route::post('/participate', 'EventsController@participate');
        Route::delete('/leave', 'EventsController@leave');
    });

    Route::resource('events', 'EventsController');

    /**
     * Users
     */
    Route::group(['prefix' => 'user'], function () {
        Route::get('/events', 'UserController@events')->name('user.events');
    });


    /**
     * Comments
     */
    Route::resource('comments', 'CommentsController');

    /**
     * Settings
     */
    Route::get('settings', 'SettingsController@index');
    Route::post('settings', 'SettingsController@save');
    Route::post('settings/avatar', 'SettingsController@saveAvatar');
});
