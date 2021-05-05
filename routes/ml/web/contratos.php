<?php

use App\Http\Controllers\Web\Contratos\ContratosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('contratos')->group(function () {
            Route::get('', [ContratosController::class, 'index']);
            Route::get('{orcamento}', [ContratosController::class, 'show']);
            Route::post('', [ContratosController::class, 'store']);
            Route::put('{orcamento}', [ContratosController::class, 'update']);
            Route::delete('{orcamento}', [ContratosController::class, 'destroy']);
            Route::put('{orcamento}/prorrogacao', [ContratosController::class, 'prorrogacao']);
        });
    });
});
