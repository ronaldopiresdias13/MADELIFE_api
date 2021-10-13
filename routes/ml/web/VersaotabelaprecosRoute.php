<?php

use App\Http\Controllers\Web\Versaotabelaprecos\VersaotabelaprecosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    // Route::get('agendamentos', [AgendamentosController::class, 'index']);
    Route::post('web/versaotabelapreco', [VersaotabelaprecosController::class, 'store']);
    // Route::get('agendamentos/{agendamento}', [AgendamentosController::class, 'show']);
    // Route::put('agendamentos/{agendamento}', [AgendamentosController::class, 'update']);
    // Route::delete('agendamentos/{agendamento}', [AgendamentosController::class, 'destroy']);
});
