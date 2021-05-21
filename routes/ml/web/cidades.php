<?php


use Illuminate\Support\Facades\Route;


Route::get('cidades', 'Api\CidadesController@index');
Route::post('cidades', 'Api\CidadesController@store');
Route::get('cidades/{cidade}', 'Api\CidadesController@show');
Route::put('cidades/{cidade}', 'Api\CidadesController@update');
Route::delete('cidades/{cidade}', 'Api\CidadesController@destroy');


