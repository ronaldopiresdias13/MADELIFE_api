<?php

use Illuminate\Support\Facades\Route;


Route::get('tipoprodutos', 'Api\TipoprodutosController@index');
Route::post('tipoprodutos', 'Api\TipoprodutosController@store');
Route::get('tipoprodutos/{tipoproduto}', 'Api\TipoprodutosController@show');
Route::put('tipoprodutos/{tipoproduto}', 'Api\TipoprodutosController@update');
Route::delete('tipoprodutos/{tipoproduto}', 'Api\TipoprodutosController@destroy');
