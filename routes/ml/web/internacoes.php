<?php

use App\Http\Controllers\Web\Internacoes\InternacoesController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {

        Route::get('listInternacoesPorPaciente/{paciente}', [InternacoesController::class, 'listInternacoesPorPaciente']);
        Route::post('internacoes',  [InternacoesController::class, 'store']);
        Route::get('internacoes/{internacoes}',  [InternacoesController::class, 'show']);
        Route::put('internacoes/{internacoes}',  [InternacoesController::class, 'update']);
        Route::delete('internacoes/{internacoes}',  [InternacoesController::class, 'destroy']);
    });
});
