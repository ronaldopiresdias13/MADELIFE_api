<?php

use Illuminate\Support\Facades\Route;


Route::get('tipopessoas', 'Api\TipopessoasController@index');
Route::post('tipopessoas', 'Api\TipopessoasController@store');
Route::get('tipopessoas/{tipopessoa}', 'Api\TipopessoasController@show');
Route::put('tipopessoas/{tipopessoa}', 'Api\TipopessoasController@update');
Route::delete('tipopessoas/{tipopessoa}', 'Api\TipopessoasController@destroy');
