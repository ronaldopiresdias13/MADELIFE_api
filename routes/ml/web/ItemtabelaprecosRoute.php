<?php

use App\Http\Controllers\Web\Itemtabelaprecos\ItemtabelaprecosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('web/itemtabelapreco',                      [ItemtabelaprecosController::class, 'index']);
    Route::post('web/itemtabelapreco',                     [ItemtabelaprecosController::class, 'store']);
    Route::get('web/itemtabelapreco/{itemtabelapreco}',    [ItemtabelaprecosController::class, 'show']);
    Route::put('web/itemtabelapreco/{itemtabelapreco}',    [ItemtabelaprecosController::class, 'update']);
    Route::delete('web/itemtabelapreco/{itemtabelapreco}', [ItemtabelaprecosController::class, 'destroy']);
});
