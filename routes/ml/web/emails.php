<?php

use Illuminate\Support\Facades\Route;

Route::get('emails', 'Api\EmailsController@index');
Route::post('emails', 'Api\EmailsController@store');
Route::get('emails/{email}', 'Api\EmailsController@show');
Route::put('emails/{email}', 'Api\EmailsController@update');
Route::delete('emails/{email}', 'Api\EmailsController@destroy');
