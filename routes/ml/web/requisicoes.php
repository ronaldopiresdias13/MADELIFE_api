<?php


use Illuminate\Support\Facades\Route;

Route::get('requisicoes', 'Api\RequisicoesController@index');
Route::post('requisicoes', 'Api\RequisicoesController@store');
Route::get('requisicoes/{requisicao}', 'Api\RequisicoesController@show');
Route::put('requisicoes/{requisicao}', 'Api\RequisicoesController@update');
Route::delete('requisicoes/{requisicao}', 'Api\RequisicoesController@destroy');
