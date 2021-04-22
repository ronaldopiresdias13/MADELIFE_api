<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('areaClinica')->group(function () {
            Route::get('profissionais/historicopacientesprestador/{prestador}', 'Api\Web\PrestadoresController@historicopacientesprestador');
        });
    });
});
