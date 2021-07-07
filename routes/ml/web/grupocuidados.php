<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('areaClinica')->group(function () {
            Route::get('grupocuidados', 'Api\Web\AreaClinica\GrupocuidadosController@index');
            Route::post('grupocuidados', 'Api\Web\AreaClinica\GrupocuidadosController@store');
            Route::get('grupocuidados/{grupocuidado}', 'Api\Web\AreaClinica\GrupocuidadosController@show');
            Route::put('grupocuidados/{grupocuidado}', 'Api\Web\AreaClinica\GrupocuidadosController@update');
            Route::delete('grupocuidados/{grupocuidado}', 'Api\Web\AreaClinica\GrupocuidadosController@destroy');
            Route::delete('grupocuidados/apagarCuidadoGrupoCuidado/{cuidadoGrupocuidado}', 'Api\Web\AreaClinica\GrupocuidadosController@apagarCuidadoGrupoCuidado');
        });
    });
});
