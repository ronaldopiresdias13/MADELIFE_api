<?php


use Illuminate\Support\Facades\Route;

Route::get('cotacaoproduto', 'Api\CotacaoProdutoController@index');
Route::post('cotacaoproduto', 'Api\CotacaoProdutoController@store');
Route::get('cotacaoproduto/{cotacaoproduto}', 'Api\CotacaoProdutoController@show');
Route::put('cotacaoproduto/{cotacaoproduto}', 'Api\CotacaoProdutoController@update');
Route::delete('cotacaoproduto/{cotacaoproduto}', 'Api\CotacaoProdutoController@destroy');
