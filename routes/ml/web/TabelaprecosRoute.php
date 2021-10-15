<?php

use App\Http\Controllers\Web\Tabelaprecos\TabelaprecosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('web/tabelapreco',                  [TabelaprecosController::class, 'index']);
    Route::post('web/tabelapreco',                 [TabelaprecosController::class, 'store']);
    Route::get('web/tabelapreco/{tabelapreco}',    [TabelaprecosController::class, 'show']);
    Route::put('web/tabelapreco/{tabelapreco}',    [TabelaprecosController::class, 'update']);
    Route::delete('web/tabelapreco/{tabelapreco}', [TabelaprecosController::class, 'destroy']);
});
