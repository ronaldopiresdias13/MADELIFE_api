<?php

use Illuminate\Support\Facades\Route;

Route::prefix('web')->group(function () {
    Route::prefix('financeiro')->group(function () {
        Route::get('getCategorias', 'Api\Web\Financeiro\CnabsController@getCategorias');

        Route::post('gerarCnab', 'Api\Web\Financeiro\CnabsController@gerarCnab');
        Route::post('mudarSituacao', 'Api\Web\Financeiro\CnabsController@mudarSituacao');

        Route::get('downloadCnab/{id}', 'Api\Web\Financeiro\CnabsController@downloadCnab');
        Route::get('getCnabs', 'Api\Web\Financeiro\CnabsController@getCnabs');
    });
});
