<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('estoque')->group(function () {
            Route::get('movimentacaoEstoque', 'Api\Web\Estoque\ProdutosController@movimentacaoEstoque');
        });
    });
});
