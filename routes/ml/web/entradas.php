<?php

use Illuminate\Support\Facades\Route;

Route::get('entradas', 'Api\EntradasController@index');
Route::post('entradas', 'Api\EntradasController@store');
Route::post('entradas/cadastrarFornecedor', 'Api\EntradasController@cadastrarFornecedor');
Route::get('entradas/{entrada}', 'Api\EntradasController@show');
Route::put('entradas/{entrada}', 'Api\EntradasController@update');
Route::delete('entradas/{entrada}', 'Api\EntradasController@destroy');
