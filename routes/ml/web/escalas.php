<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {

    /*----------------- Web -----------------*/
    Route::prefix('web')->group(function () {
        Route::prefix('convenio')->group(function () {
            Route::get('escalas/dashboard', 'Api\Web\Convenio\EscalasController@dashboardConvenio');
            Route::get('escalas/listEscalasByIdCliente', 'Api\Web\Convenio\EscalasController@listEscalasByIdCliente');
            Route::get('dashboard/relatorioDiario', 'Api\Web\Convenio\EscalasController@relatorioDiario');
            Route::get('dashboard/relatorioProdutividade', 'Api\Web\Convenio\EscalasController@relatorioProdutividade');
            Route::get('escalas/dashboardPegarTodosOsRegistrosPorIdDaEmpresa', 'Api\Web\Convenio\EscalasController@dashboardConvenio');
        });
    });
});