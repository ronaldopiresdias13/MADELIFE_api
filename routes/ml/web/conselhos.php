<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('departamentoPessoal')->group(function () {
            Route::get('buscalistadeconselhospodidpessoa/{pessoa}', 'Api\Web\PrestadoresController@buscalistadeconselhospodidpessoa');
            Route::post('salvarconselho', 'Api\Web\PrestadoresController@salvarconselho');
            Route::delete('deletarconselho/{conselho}', 'Api\Web\PrestadoresController@deletarconselho');
        });
    });
});

Route::get('conselhos', 'Api\ConselhosController@index');
Route::post('conselhos', 'Api\ConselhosController@store');
Route::get('conselhos/{conselho}', 'Api\ConselhosController@show');
Route::put('conselhos/{conselho}', 'Api\ConselhosController@update');
Route::delete('conselhos/{conselho}', 'Api\ConselhosController@destroy');
