<?php


use Illuminate\Support\Facades\Route;

Route::get('requisicaoprodutos', 'Api\RequisicaoProdutosController@index');
Route::post('requisicaoprodutos', 'Api\RequisicaoProdutosController@store');
Route::get('requisicaoprodutos/{requisicaoProduto}', 'Api\RequisicaoProdutosController@show');
Route::put('requisicaoprodutos/{requisicaoProduto}', 'Api\RequisicaoProdutosController@update');
Route::delete('requisicaoprodutos/{requisicaoProduto}', 'Api\RequisicaoProdutosController@destroy');
