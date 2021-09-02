<?php

use App\Http\Controllers\HistoricosController;
use  App\Http\Controllers\Web\Escalas\EscalasController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('escalas', 'Api\EscalasController@clonarEscalas');

    Route::prefix('web')->group(function () {
        Route::prefix('convenio')->group(function () {
            Route::get('escalas/dashboard', 'Api\Web\Convenio\EscalasController@dashboardConvenio');
            Route::get('escalas/listEscalasByIdCliente', 'Api\Web\Convenio\EscalasController@listEscalasByIdCliente');
            Route::get('dashboard/relatorioDiario', 'Api\Web\Convenio\EscalasController@relatorioDiario');
            Route::get('dashboard/relatorioProdutividade', 'Api\Web\Convenio\EscalasController@relatorioProdutividade');
            Route::get('escalas/dashboardPegarTodosOsRegistrosPorIdDaEmpresa', 'Api\Web\Convenio\EscalasController@dashboardConvenio');
        });
        Route::prefix('escalas')->group(function () {
            Route::get('medicao', [EscalasController::class, 'medicao']);
            Route::get('historicoEscalasPorPrestadorId', 'Api\Web\EscalasController@dashboardPegarTodosPacientesporId');
            Route::post('dashboardClonarEscalas', 'Api\EscalasController@dashboardClonarEscalas');
            Route::get('escalasPorPeriodo', 'Api\Web\EscalasController@EscalasPorPeriodo');
        });
        Route::prefix('departamentoPessoal')->group(function () {
            Route::post('escalas/updateServicoOfEscala/{escala}', 'Api\Web\DepartamentoPessoal\EscalasController@updateServicoOfEscala');
        });
        Route::prefix('responsavel')->group(function () {
            Route::get('escalas/listEscalasByIdResponsavel', 'Api\Web\Responsavel\EscalasController@listEscalasByIdResponsavel');
            Route::get('escalas/listEscalasByIdOrdemServico/{ordemservico}', 'Api\Web\Responsavel\EscalasController@listEscalasByIdOrdemServico');
            Route::post('escalas/assinar', 'Api\Web\Responsavel\EscalasController@assinar');
            Route::get('escalas/dashboard', 'Api\Web\Responsavel\EscalasController@dashboard');
            Route::get('escalas/dashboardPegarTodosOsRegistrosPorIdDaEmpresa', 'Api\Web\Responsavel\EscalasController@dashboardPegarTodosOsRegistrosPorIdDaEmpresa');
        });
    });

    Route::get('escalas', 'Api\EscalasController@index');
    Route::get('escalas/{escala}', 'Api\EscalasController@show');
    Route::post('escalas', 'Api\EscalasController@store');
    Route::put('escalas/{escala}', 'Api\EscalasController@update');
    Route::delete('escalas/{escala}', 'Api\EscalasController@destroy');
    Route::get('escalas/empresa/{empresa}/dia', 'Api\EscalasController@buscaescalasdodia');
    Route::get('escalas/paciente/{paciente}/data1/{data1}/data2/{data2}', 'Api\EscalasController@buscaPontosPorPeriodoEPaciente');
});
