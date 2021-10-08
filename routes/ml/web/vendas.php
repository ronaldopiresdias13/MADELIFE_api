<?php

use Illuminate\Support\Facades\Route;

Route::post('vendas', 'Api\VendasController@store');
Route::get('vendas/{venda}', 'Api\VendasController@show');
Route::put('vendas/{venda}', 'Api\VendasController@update');
Route::delete('vendas/{venda}', 'Api\VendasController@destroy');
Route::post('vendas/cadastrarCliente', 'Api\VendasController@cadastrarCliente');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('vendas', 'Api\VendasController@index');
    Route::get('web/vendas/{venda}', 'Web\Vendas\VendasController@show');
});
