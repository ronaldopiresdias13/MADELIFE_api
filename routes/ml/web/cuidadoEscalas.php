<?php


use Illuminate\Support\Facades\Route;

Route::get('cuidadoEscalas', 'Api\CuidadoEscalasController@index');
Route::post('cuidadoEscalas', 'Api\CuidadoEscalasController@store');
Route::post('cuidadoEscalas/adicionarCuidadosNaEscala', 'Api\CuidadoEscalasController@adicionarCuidadosNaEscala');
Route::get('cuidadoEscalas/{cuidadoEscala}', 'Api\CuidadoEscalasController@show');
Route::put('cuidadoEscalas/{cuidadoEscala}', 'Api\CuidadoEscalasController@update');
Route::delete('cuidadoEscalas/{cuidadoEscala}', 'Api\CuidadoEscalasController@destroy');
