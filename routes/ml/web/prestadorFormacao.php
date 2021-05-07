<?php


use Illuminate\Support\Facades\Route;

Route::get('prestadorFormacao', 'Api\PrestadorFormacaoController@index');
Route::post('prestadorFormacao', 'Api\PrestadorFormacaoController@store');
Route::get('prestadorFormacao/{prestadorFormacao}', 'Api\PrestadorFormacaoController@show');
Route::put('prestadorFormacao/{prestadorFormacao}', 'Api\PrestadorFormacaoController@update');
Route::delete('prestadorFormacao/{prestadorFormacao}', 'Api\PrestadorFormacaoController@destroy');
Route::get(
    'prestadorFormacao/{prestadorFormacao}/downloadFile',
    'Api\PrestadorFormacaoController@downloadFile'
); // Custon
