<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::delete('profissionalBeneficio/{profissionalBeneficio}', 'Web\ProfissionalBeneficio\ProfissionalBeneficioController@destroy');
    });
});
