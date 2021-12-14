<?php

use App\Http\Controllers\Web\Protocolos\ProtocoloAvaliacaoEstomasController;
use App\Http\Controllers\Web\Protocolos\ProtocoloAvaliacaoFeridaController;
use App\Http\Controllers\Web\Protocolos\ProtocoloMedicacaoController;
use App\Models\ProtocoloAvaliacaoEstoma;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('protocoloEstoma')->group(function () {
            Route::get('estomas', [ProtocoloAvaliacaoEstomasController::class ,'index']);
            Route::post('estoma', [ProtocoloAvaliacaoEstomasController::class ,'store']);
            Route::get('estoma/{protocoloAvaliacaoEstoma}', [ProtocoloAvaliacaoEstomasController::class, 'show']);
            Route::put('estoma/{protocoloAvaliacaoEstoma}', [ProtocoloAvaliacaoEstomasController::class, 'update']);
            Route::delete('estoma/{protocoloAvaliacaoEstoma}', [ProtocoloAvaliacaoEstomasController::class, 'destroy']);
        });
        Route::prefix('protocoloFerida')->group(function () {
            Route::get('feridas', [ProtocoloAvaliacaoFeridaController::class ,'index']);
            Route::post('ferida', [ProtocoloAvaliacaoFeridaController::class ,'store']);
            Route::get('ferida/{protocoloAvaliacaoFerida}', [ProtocoloAvaliacaoFeridaController::class, 'show']);
            Route::put('ferida/{protocoloAvaliacaoFerida}', [ProtocoloAvaliacaoFeridaController::class, 'update']);
            Route::delete('ferida/{protocoloAvaliacaoFerida}', [ProtocoloAvaliacaoFeridaController::class, 'destroy']);
        });
        Route::prefix('protocoloMedicacao')->group(function () {
            Route::get('medicacoes', [ProtocoloMedicacaoController::class ,'index']);
            Route::post('medicacao', [ProtocoloMedicacaoController::class ,'store']);
            Route::get('medicacao/{protocoloMedicacao}', [ProtocoloMedicacaoController::class, 'show']);
            Route::put('medicacao/{protocoloMedicacao}', [ProtocoloMedicacaoController::class, 'update']);
            Route::delete('medicacao/{protocoloMedicacao}', [ProtocoloMedicacaoController::class, 'destroy']);
        });
    });
});