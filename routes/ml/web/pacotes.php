<?php

use App\Http\Controllers\Api\Web\GestaoOrcamentaria\PacotesController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('gestaoOrcamentaria')->group(function () {
            Route::get('pacotes', [PacotesController::class, 'index']);
            Route::get('pacotes/{pacote}', [PacotesController::class, 'show']);
            Route::post('pacotes', [PacotesController::class, 'store']);
            Route::put('pacotes/{pacote}', [PacotesController::class, 'update']);
            Route::delete('pacotes/{pacote}', [PacotesController::class, 'destroy']);
            Route::delete('pacotes/excluirItemPacoteServico/{pacoteservico}', [PacotesController::class, 'excluirItemPacoteServico']);
            Route::delete('pacotes/excluirItemPacoteProduto/{pacoteproduto}', [PacotesController::class, 'excluirItemPacoteProduto']);
        });
    });
});
