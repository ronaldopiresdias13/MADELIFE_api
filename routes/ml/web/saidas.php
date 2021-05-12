<?php


use Illuminate\Support\Facades\Route;

Route::get('saidas', 'Api\SaidasController@index');
Route::post('saidas', 'Api\SaidasController@store');
Route::get('saidas/{saida}', 'Api\SaidasController@show');
Route::put('saidas/{saida}', 'Api\SaidasController@update');
Route::delete('saidas/{saida}', 'Api\SaidasController@destroy');
