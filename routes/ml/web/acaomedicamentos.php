<?php


use Illuminate\Support\Facades\Route;

Route::get('acaomedicamentos', 'Api\AcaomedicamentosController@index');
Route::post('acaomedicamentos', 'Api\AcaomedicamentosController@store');
Route::get('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@show');
Route::put('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@update');
Route::delete('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@destroy');
