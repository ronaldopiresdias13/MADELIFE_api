<?php

use App\Http\Controllers\Web\Contratos\ContratosclienteController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        // Route::get('contratoscliente', [ContratosclienteController::class, 'index']);
        // Route::get('contratoscliente/{contrato}', [ContratosclienteController::class, 'show']);
        // Route::post('contratoscliente', [ContratosclienteController::class, 'store']);
        // Route::put('contratoscliente/{contrato}', [ContratosclienteController::class, 'update']);
        // Route::delete('contratoscliente/{contrato}', [ContratosclienteController::class, 'destroy']);
    });
});
