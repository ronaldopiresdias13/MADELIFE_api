<?php

use Illuminate\Support\Facades\Route;

Route::get('vendas', 'Api\VendasController@index');
Route::post('vendas', 'Api\VendasController@store');
Route::get('vendas/{venda}', 'Api\VendasController@show');
Route::put('vendas/{venda}', 'Api\VendasController@update');
Route::delete('vendas/{venda}', 'Api\VendasController@destroy');
Route::post('vendas/cadastrarCliente', 'Api\VendasController@cadastrarCliente');
