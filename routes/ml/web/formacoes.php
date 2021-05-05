<?php

use App\Http\Controllers\Web\Formacoes\FormacoesController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('formacoes')->group(function () {
            Route::get('', [FormacoesController::class, 'index']);
        });
    });
});
