<?php

use App\Http\Controllers\Web\Ordemservicos\OrdemservicosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('ordemservicos')->group(function () {
            Route::get('', [OrdemservicosController::class, 'index']);
        });
    });
});
