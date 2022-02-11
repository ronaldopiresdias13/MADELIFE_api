<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('areaClinica')->group(function () {
            Route::get('cuidados', 'Api\Web\AreaClinica\CuidadosController@index');
            Route::post('cuidados', 'Api\Web\AreaClinica\CuidadosController@store');
            Route::get('cuidados/{cuidado}', 'Api\Web\AreaClinica\CuidadosController@show');
            Route::put('cuidados/{cuidado}', 'Api\Web\AreaClinica\CuidadosController@update');
            Route::delete('cuidados/{cuidado}', 'Api\Web\AreaClinica\CuidadosController@destroy');
            Route::get('cuidados/count/{empresa}', 'Api\Web\AreaClinica\CuidadosController@quantidadecuidados');
        });
    });
});
