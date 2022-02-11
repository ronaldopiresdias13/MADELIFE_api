<?php

use Illuminate\Support\Facades\Route;

Route::get('outros', 'Api\OutrosController@index');
Route::post('outros', 'Api\OutrosController@store');
Route::get('outros/{outro}', 'Api\OutrosController@show');
Route::put('outros/{outro}', 'Api\OutrosController@update');
Route::delete('outros/{outro}', 'Api\OutrosController@destroy');
