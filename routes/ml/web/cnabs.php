<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('financeiro')->group(function () {
            Route::get('getCategorias', 'Api\Web\Financeiro\CnabsController@getCategorias');

            Route::post('gerarCnab', 'Api\Web\Financeiro\CnabsController@gerarCnab');
            Route::post('mudarSituacao', 'Api\Web\Financeiro\CnabsController@mudarSituacao');

            Route::get('downloadCnab/{id}', 'Api\Web\Financeiro\CnabsController@downloadCnab');
            Route::get('getCnabs', 'Api\Web\Financeiro\CnabsController@getCnabs');
        });
    });
});

Route::get('cnabs', 'Api\CnabsController@index');
Route::post('cnabs', 'Api\CnabsController@store');
Route::get('cnabs/{cnab}/{tipo}', 'Api\CnabsController@show');
Route::put('cnabs/{cnab}', 'Api\CnabsController@update');
Route::delete('cnabs/{cnab}', 'Api\CnabsController@destroy');
