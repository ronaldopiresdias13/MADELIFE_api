<?php

use App\Http\Controllers\Web\Notificacoes\NotificacoesController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('web/notificacoes', [NotificacoesController::class, 'index']);
    Route::get('web/notificacoes/{notificacao}', [NotificacoesController::class, 'show']);
    Route::post('web/notificacoes', [NotificacoesController::class, 'store']);
    Route::put('web/notificacoes/{notificacao}', [NotificacoesController::class, 'update']);
    Route::delete('web/notificacoes/{notificacao}', [NotificacoesController::class, 'destroy']);
});
