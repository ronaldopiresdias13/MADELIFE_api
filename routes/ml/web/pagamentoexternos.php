<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('diretoria')->group(function () {
            Route::get('groupByPagamentoByMesAndEmpresaId/externo', 'Api\Web\DepartamentoPessoal\PagamentoexternosController@groupByPagamentoByMesAndEmpresaId');
        });
    });
});
