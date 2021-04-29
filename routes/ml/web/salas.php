<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('agendamento')->group(function () {
            Route::get('salas', 'Api\Web\Agendamento\SalasController@index');
            Route::post('salas', 'Api\Web\Agendamento\SalasController@store');
            Route::get('salas/{sala}', 'Api\Web\Agendamento\SalasController@show');
            Route::put('salas/{sala}', 'Api\Web\Agendamento\SalasController@update');
            Route::delete('salas/{sala}', 'Api\Web\Agendamento\SalasController@destroy');
        });
    });
});
