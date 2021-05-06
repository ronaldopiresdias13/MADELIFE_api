<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('recursosHumanos')->group(function () {
            Route::get('beneficios', 'Api\Web\RecursosHumanos\BeneficiosController@index');
            Route::post('beneficios', 'Api\Web\RecursosHumanos\BeneficiosController@store');
            Route::get('beneficios/{beneficio}', 'Api\Web\RecursosHumanos\BeneficiosController@show');
            Route::put('beneficios/{beneficio}', 'Api\Web\RecursosHumanos\BeneficiosController@update');
            Route::delete('beneficios/{beneficio}', 'Api\Web\RecursosHumanos\BeneficiosController@destroy');
        });
    });
});
