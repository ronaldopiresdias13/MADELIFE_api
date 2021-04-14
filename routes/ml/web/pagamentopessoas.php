<?php

use Illuminate\Support\Facades\Route;

Route::prefix('web')->group(function () {
    Route::prefix('financeiro')->group(function () {
        Route::get('listPagamentosByEmpresaId', 'Api\Web\Financeiro\PagamentopessoasController@listPagamentosByEmpresaId');
    });
});
