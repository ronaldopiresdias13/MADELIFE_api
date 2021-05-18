<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('departamentoPessoal')->group(function () {
            Route::get('buscalistadetelefonespodidpessoa/{pessoa}', 'Api\Web\PrestadoresController@buscalistadetelefonespodidpessoa');
            Route::delete('deletartelefone/{pessoaTelefone}', 'Api\Web\PrestadoresController@deletartelefone');
        });
    });
});

Route::get('telefones', 'Api\TelefonesController@index');
Route::post('telefones', 'Api\TelefonesController@store');
Route::get('telefones/{telefone}', 'Api\TelefonesController@show');
Route::put('telefones/{telefone}', 'Api\TelefonesController@update');
Route::delete('telefones/{telefone}', 'Api\TelefonesController@destroy');
