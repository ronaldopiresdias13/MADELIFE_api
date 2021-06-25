<?php

use App\Http\Controllers\Web\Impostos\ImpostosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::get('impostos', [ImpostosController::class, 'index']);
        Route::post('impostos', [ImpostosController::class, 'store']);
        Route::get('impostos/{imposto}', [ImpostosController::class, 'show']);
        Route::put('impostos/{imposto}', [ImpostosController::class, 'update']);
        Route::delete('impostos/{imposto}', [ImpostosController::class, 'destroy']);
    });
});
