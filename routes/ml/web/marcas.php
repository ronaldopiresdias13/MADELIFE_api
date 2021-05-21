<?php

use Illuminate\Support\Facades\Route;

Route::get('marcas', 'Api\MarcasController@index');
Route::post('marcas', 'Api\MarcasController@store');
Route::get('marcas/{marca}', 'Api\MarcasController@show');
Route::put('marcas/{marca}', 'Api\MarcasController@update');
Route::delete('marcas/{marca}', 'Api\MarcasController@destroy');
