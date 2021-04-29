<?php

use App\Http\Controllers\Api\Web\Agendamento\AgendamentosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('agendamento')->group(function () {
            Route::get('agendamentos', [AgendamentosController::class, 'index']);
            Route::post('agendamentos', [AgendamentosController::class, 'store']);
            Route::get('agendamentos/{agendamento}', [AgendamentosController::class, 'show']);
            Route::put('agendamentos/{agendamento}', [AgendamentosController::class, 'update']);
            Route::delete('agendamentos/{agendamento}', [AgendamentosController::class, 'destroy']);
        });
    });
});
