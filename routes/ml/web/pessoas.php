<?php

use Illuminate\Support\Facades\Route;

Route::get('pessoas', 'Api\PessoasController@index');
Route::post('pessoas', 'Api\PessoasController@store');
Route::get('pessoas/{pessoa}', 'Api\PessoasController@show');
Route::put('pessoas/{pessoa}', 'Api\PessoasController@update');
Route::delete('pessoas/{pessoa}', 'Api\PessoasController@destroy');
