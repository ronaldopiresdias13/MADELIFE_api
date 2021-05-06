<?php

use App\Http\Controllers\Web\Formacoes\FormacoesController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('departamentoPessoal')->group(function () {
            Route::get('buscalistadeenderecospodidpessoa/{pessoa}', 'Api\Web\PrestadoresController@buscalistadeenderecospodidpessoa');
            Route::post('salvarendereco', 'Api\Web\PrestadoresController@salvarendereco');
            Route::delete('deletarendereco/{pessoaEndereco}', 'Api\Web\PrestadoresController@deletarendereco');
        });
    });
});
