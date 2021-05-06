<?php


use Illuminate\Support\Facades\Route;

Route::get('requisicaoprodudos', 'Api\RequisicaoProdutosController@index');
Route::post('requisicaoprodudos', 'Api\RequisicaoProdutosController@store');
Route::get('requisicaoprodudos/{requisicaoProduto}', 'Api\RequisicaoProdutosController@show');
Route::put('requisicaoprodudos/{requisicaoProduto}', 'Api\RequisicaoProdutosController@update');
Route::delete('requisicaoprodudos/{requisicaoProduto}', 'Api\RequisicaoProdutosController@destroy');
