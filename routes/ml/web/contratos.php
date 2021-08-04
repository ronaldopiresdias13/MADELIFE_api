<?php

use App\Http\Controllers\Web\Contratos\ContratosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::get('contratos', [ContratosController::class, 'index']);
        Route::get('contratos/listaContratosDoClienteNoPeriodo/{cliente}', [ContratosController::class, 'listaContratosDoClienteNoPeriodo']);
        Route::get('contratos/{orcamento}', [ContratosController::class, 'show']);
        Route::post('contratos', [ContratosController::class, 'store']);
        Route::put('contratos/{orcamento}', [ContratosController::class, 'update']);

        Route::put('contratos/desativar/{orcamento}', [ContratosController::class, 'desativarContrato']);

        Route::delete('contratos/{orcamento}', [ContratosController::class, 'destroy']);
        Route::put('contratos/{orcamento}/prorrogacao', [ContratosController::class, 'prorrogacao']);

        Route::prefix('gestaoOrcamentaria')->group(function () {
            Route::get('contratos/getAllOrdensServicos', 'Api\Web\GestaoOrcamentaria\OrdemservicosController@getAllOrdensServicos');
            Route::get('contratos/dashboardGroupByMotivoDesativados', 'Api\Web\GestaoOrcamentaria\OrdemservicosController@dashboardGroupByMotivoDesativados');
            Route::get('contratos/dashboardGroupByStatusAtivadosDesativados', 'Api\Web\GestaoOrcamentaria\OrdemservicosController@dashboardGroupByStatusAtivadosDesativados');
        });
    });
});
