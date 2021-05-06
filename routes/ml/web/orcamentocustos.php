<?php


use Illuminate\Support\Facades\Route;

Route::get('orcamentocustos', 'Api\OrcamentoCustosController@index');
Route::post('orcamentocustos', 'Api\OrcamentoCustosController@store');
Route::get('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@show');
Route::put('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@update');
Route::delete('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@destroy');
