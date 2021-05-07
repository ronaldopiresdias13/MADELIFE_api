<?php

use Illuminate\Support\Facades\Route;

Route::get('users', 'Api\UsersController@index');
Route::post('users', 'Api\UsersController@store');
Route::get('users/{user}', 'Api\UsersController@show');
Route::put('users/{user}', 'Api\UsersController@update');
Route::put('users/{user}/updateEmail', 'Api\UsersController@updateEmail');
Route::delete('users/{user}', 'Api\UsersController@destroy');
