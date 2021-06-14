<?php

use Illuminate\Support\Facades\Route;

Route::get('medicoes', 'Api\MedicoesController@index');
Route::post('medicoes', 'Api\MedicoesController@store');
Route::get('medicoes/{medicao}', 'Api\MedicoesController@show');
Route::put('medicoes/{medicao}', 'Api\MedicoesController@update');
Route::delete('medicoes/{medicao}', 'Api\MedicoesController@destroy');
