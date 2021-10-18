<?php

use App\Http\Controllers\Web\Orcs\OrcsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('orcs')->group(function () {
            Route::get('', [OrcsController::class, 'index']);
            Route::get('gerarCodigo', [OrcsController::class, 'gerarCodigoOrc']);
            Route::get('buscaquantidadeorcamentosporsituacao', [OrcsController::class, 'buscaquantidadeorcamentosporsituacao']);
            Route::get('gerarCodigoOrcamento', [OrcsController::class, 'gerarCodigoOrcamento']);
            Route::get('{orc}', [OrcsController::class, 'show']);
            Route::post('', [OrcsController::class, 'store']);
            Route::put('{orc}', [OrcsController::class, 'update']);
            Route::delete('{orc}', [OrcsController::class, 'destroy']);
            Route::post('{orc}/criarcontrato', [OrcsController::class, 'criarcontrato']);
            Route::delete('apagarOrcservico/{orcServico}', [OrcsController::class, 'apagarOrcservico']);
            Route::delete('apagarOrcproduto/{orcProduto}', [OrcsController::class, 'apagarOrcproduto']);
        });
    });
});
