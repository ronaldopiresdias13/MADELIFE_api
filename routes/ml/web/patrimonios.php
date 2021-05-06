<?php

use Illuminate\Support\Facades\Route;

Route::get('patrimonios', 'Api\PatrimoniosController@index');
Route::post('patrimonios', 'Api\PatrimoniosController@store');
Route::get('patrimonios/{patrimonio}', 'Api\PatrimoniosController@show');
Route::put('patrimonios/{patrimonio}', 'Api\PatrimoniosController@update');
Route::delete('patrimonios/{patrimonio}', 'Api\PatrimoniosController@destroy');
