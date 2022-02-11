<?php


use Illuminate\Support\Facades\Route;

Route::get('orcamentoprodutos', 'Api\OrcamentoProdutosController@index');
Route::post('orcamentoprodutos', 'Api\OrcamentoProdutosController@store');
Route::get('orcamentoprodutos/{orcamentoProduto}', 'Api\OrcamentoProdutosController@show');
Route::put('orcamentoprodutos/{orcamentoProduto}', 'Api\OrcamentoProdutosController@update');
Route::delete('orcamentoprodutos/{orcamentoProduto}', 'Api\OrcamentoProdutosController@destroy');
