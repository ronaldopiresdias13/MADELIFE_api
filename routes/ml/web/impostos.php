<?php

use Illuminate\Support\Facades\Route;

Route::get('impostos', 'Api\ImpostosController@index');
Route::post('impostos', 'Api\ImpostosController@store');
Route::get('impostos/{imposto}', 'Api\ImpostosController@show');
Route::put('impostos/{imposto}', 'Api\ImpostosController@update');
Route::delete('impostos/{imposto}', 'Api\ImpostosController@destroy');
