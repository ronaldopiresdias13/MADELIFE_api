<?php

use Illuminate\Support\Facades\Route;

Route::get('homecares', 'Api\HomecaresController@index');
Route::post('homecares', 'Api\HomecaresController@store');
Route::get('homecares/{homecare}', 'Api\HomecaresController@show');
Route::put('homecares/{homecare}', 'Api\HomecaresController@update');
Route::delete('homecares/{homecare}', 'Api\HomecaresController@destroy');
