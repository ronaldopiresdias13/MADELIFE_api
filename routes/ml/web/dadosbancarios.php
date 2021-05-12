<?php

use Illuminate\Support\Facades\Route;

Route::get('dadosbancarios', 'Api\DadosbancariosController@index');
Route::post('dadosbancarios', 'Api\DadosbancariosController@store');
Route::get('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@show');
Route::put('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@update');
Route::delete('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@destroy');
