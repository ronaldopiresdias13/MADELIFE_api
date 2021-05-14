<?php

use App\Http\Controllers\Api\Novo\Web\OrdemservicoAcessoController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('ordemservicoAcesso')->group(function () {
            Route::get('listaPorOrdemservico', [OrdemservicoAcessoController::class, 'listaDeAcessosPorOrdemservico']);
            Route::put('check/{ordemservicoAcesso}', [OrdemservicoAcessoController::class, 'checkOrdemservicoAcesso']);
        });
    });
});
