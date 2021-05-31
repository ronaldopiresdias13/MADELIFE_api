<?php

use Illuminate\Support\Facades\Route;

Route::get('monitoramentoescalas', 'Api\MonitoramentoescalasController@index');
Route::post('monitoramentoescalas', 'Api\MonitoramentoescalasController@store');
Route::get('monitoramentoescalas/{monitoramentoescala}', 'Api\MonitoramentoescalasController@show');
Route::put('monitoramentoescalas/{monitoramentoescala}', 'Api\MonitoramentoescalasController@update');
Route::delete('monitoramentoescalas/{monitoramentoescala}', 'Api\MonitoramentoescalasController@destroy');
