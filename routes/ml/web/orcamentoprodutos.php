<?php


use Illuminate\Support\Facades\Route;

Route::get('orcamentoprodutos', 'Api\OrcamentoProdutosController@index');
Route::post('orcamentoprodutos', 'Api\OrcamentoProdutosController@store');
Route::get('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@show');
Route::put('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@update');
Route::delete('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@destroy');
