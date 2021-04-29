<?php

use App\Http\Controllers\Web\Pontos\PontosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('pontos')->group(function () {
            Route::put('correcaoPontos', [PontosController::class, 'correcaoPontos']);
        });
    });
});
