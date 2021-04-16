<?php

use App\Http\Controllers\Web\Pacientes\PacientesController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('pacientes')->group(function () {
            Route::get('pacientesOfCliente', [ PacientesController::class, 'pacientesOfCliente']);
        });
    });
});
