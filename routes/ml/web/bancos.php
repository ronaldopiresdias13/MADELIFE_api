<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('departamentoPessoal')->group(function () {
            Route::get('buscalistadebancospodidpessoa/{pessoa}', 'Api\Web\PrestadoresController@buscalistadebancospodidpessoa');
            Route::post('salvarbanco', 'Api\Web\PrestadoresController@salvarbanco');
            Route::delete('deletarbanco/{dadosbancario}', 'Api\Web\PrestadoresController@deletarbanco');
        });
    });
});

Route::get('bancos', 'Api\BancosController@index');
Route::post('bancos', 'Api\BancosController@store');
Route::get('bancos/{banco}', 'Api\BancosController@show');
Route::put('bancos/{banco}', 'Api\BancosController@update');
Route::delete('bancos/{banco}', 'Api\BancosController@destroy');
