<?php

use App\Http\Controllers\Api\Web\DepartamentoPessoal\PagamentoexternosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('diretoria')->group(function () {
            Route::get('groupByPagamentoByMesAndEmpresaId/externo', 'Api\Web\DepartamentoPessoal\PagamentoexternosController@groupByPagamentoByMesAndEmpresaId');
        });
        Route::prefix('pagamentoexterno')->group(function () {
            Route::get('list', [PagamentoexternosController::class, 'list']);
            Route::get('gerarlist', [PagamentoexternosController::class, 'gerarlist']);
            Route::post('create', [PagamentoexternosController::class, 'create']);
            Route::post('createlist', [PagamentoexternosController::class, 'createlist']);
            Route::post('atualizarPagamentosExternos', [PagamentoexternosController::class, 'atualizarPagamentosExternos']);
            Route::delete('apagarpagamento/{pagamentoexterno}', [PagamentoexternosController::class, 'apagarpagamento']);
        });
    });
});
