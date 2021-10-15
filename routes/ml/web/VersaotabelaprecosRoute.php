<?php

use App\Http\Controllers\Web\Versaotabelaprecos\VersaotabelaprecosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('web/versaotabelapreco',                        [VersaotabelaprecosController::class, 'index']);
    Route::post('web/versaotabelapreco',                       [VersaotabelaprecosController::class, 'store']);
    Route::get('web/versaotabelapreco/{versaotabelapreco}',    [VersaotabelaprecosController::class, 'show']);
    Route::put('web/versaotabelapreco/{versaotabelapreco}',    [VersaotabelaprecosController::class, 'update']);
    Route::delete('web/versaotabelapreco/{versaotabelapreco}', [VersaotabelaprecosController::class, 'destroy']);
});
