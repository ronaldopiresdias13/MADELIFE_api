<?php

use Illuminate\Support\Facades\Route;

Route::get('historicoorcamentos', 'Api\HistoricoorcamentosController@index');
Route::post('historicoorcamentos', 'Api\HistoricoorcamentosController@store');
Route::get('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@show');
Route::put('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@update');
Route::delete('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@destroy');
