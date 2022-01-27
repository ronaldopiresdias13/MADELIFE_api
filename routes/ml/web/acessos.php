<?php


use Illuminate\Support\Facades\Route;

Route::get('acessos', 'Api\AcessosController@index');
Route::post('acessos', 'Api\AcessosController@store');
Route::get('acessos/{acesso}', 'Api\AcessosController@show');
Route::put('acessos/{acesso}', 'Api\AcessosController@update');
Route::delete('acessos/{acesso}', 'Api\AcessosController@destroy');

