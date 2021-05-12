<?php


use Illuminate\Support\Facades\Route;

Route::get('relatorios', 'Api\RelatoriosController@index');
Route::post('relatorios', 'Api\RelatoriosController@store');
Route::get('relatorios/{relatorio}', 'Api\RelatoriosController@show');
Route::put('relatorios/{relatorio}', 'Api\RelatoriosController@update');
Route::delete('relatorios/{relatorio}', 'Api\RelatoriosController@destroy');
Route::get('relatoriosOfOrdemservico/{ordemservico}', 'Api\RelatoriosController@relatoriosOfOrdemservico');
Route::get('relatorios/escala/{escala}', 'Api\RelatoriosController@buscaRelatoriosDaEscala');
