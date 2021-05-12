<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('recursosHumanos')->group(function () {
            Route::get('cargos', 'Api\Web\RecursosHumanos\CargosController@index');
            Route::post('cargos', 'Api\Web\RecursosHumanos\CargosController@store');
            Route::get('cargos/{cargo}', 'Api\Web\RecursosHumanos\CargosController@show');
            Route::put('cargos/{cargo}', 'Api\Web\RecursosHumanos\CargosController@update');
            Route::delete('cargos/{cargo}', 'Api\Web\RecursosHumanos\CargosController@destroy');
        });
    });
});

