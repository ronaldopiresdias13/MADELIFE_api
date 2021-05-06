<?php


use Illuminate\Support\Facades\Route;

Route::get('relatorioescalas', 'Api\RelatorioescalasController@index');
Route::post('relatorioescalas/{escala}', 'Api\RelatorioescalasController@store');
Route::get('relatorioescalas/{relatorioescala}', 'Api\RelatorioescalasController@show');
Route::put('relatorioescalas/{relatorioescala}', 'Api\RelatorioescalasController@update');
Route::delete('relatorioescalas/{relatorioescala}', 'Api\RelatorioescalasController@destroy');
