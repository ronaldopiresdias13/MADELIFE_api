<?php

use App\Http\Controllers\Api\Novo\Web\EscalasController;
use App\Http\Controllers\Api\Novo\Web\PrestadoresController;
use App\Http\Controllers\Api\Novo\Web\TranscricaoProdutoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*------------- Rotas Utilizando Token -------------*/

Route::group(['middleware' => 'auth:api'], function () {

    /*----------------- Web -----------------*/
    Route::prefix('web')->group(function () {
        Route::prefix('home')->group(function () {
            Route::get('dashboard/get_dados', 'Api\Web\Home\DashboardController@get_dados');
            Route::get('dashboard/get_ocorrencias_resolvidas', 'Api\Web\Home\DashboardController@get_ocorrencias_resolvidas');

            Route::post('dashboard/resolver_ocorrencia', 'Api\Web\Home\DashboardController@resolver_ocorrencia');
            Route::get('dashboard/get_pendencias', 'Api\Web\Home\DashboardController@get_pendencias');
        });
        Route::prefix('pils')->group(function () {

             //anexosB
             Route::get('listAnexosB', 'Api\Web\AnexoBController@get_anexosB');
             Route::get('getDadosAnexoB/', 'Api\Web\AnexoBController@getDadosAnexoB');
             Route::post('store_anexob', 'Api\Web\AnexoBController@store_anexob');
             Route::get('getAnexoBEdit/{id}', 'Api\Web\AnexoBController@getAnexoBEdit');
             Route::post('update_anexob', 'Api\Web\AnexoBController@update_anexob');
 
             Route::delete('delete_anexob/{id}', 'Api\Web\AnexoBController@delete_anexob');

            //anexosA
            Route::get('listAnexosA', 'Api\Web\AnexoAController@get_anexosA');
            Route::get('getDadosAnexoA/', 'Api\Web\AnexoAController@getDadosAnexoA');
            Route::post('store_anexoa', 'Api\Web\AnexoAController@store_anexoa');
            Route::get('getAnexoAEdit/{id}', 'Api\Web\AnexoAController@getAnexoAEdit');
            Route::post('update_anexoa', 'Api\Web\AnexoAController@update_anexoa');

            Route::delete('delete_anexoa/{id}', 'Api\Web\AnexoAController@delete_anexoa');

            //pil
            Route::get('listPils', 'Api\Web\PilController@get_pils');
            Route::get('getDadosPil/', 'Api\Web\PilController@getDadosPil');
            Route::get('getPil/{id}', 'Api\Web\PilController@getPil');

            Route::get('getCuidados/', 'Api\Web\PilController@getCuidados');
            Route::get('getMedicamentos/', 'Api\Web\PilController@getMedicamentos');
            Route::post('store_pil', 'Api\Web\PilController@store_pil');
            Route::post('update_pil', 'Api\Web\PilController@update_pil');


            Route::delete('delete_pil/{id}', 'Api\Web\PilController@delete_pil');


            //nead
            Route::get('listNeads', 'Api\Web\NeadController@get_neads');
            Route::get('getDadosNead/', 'Api\Web\NeadController@getDadosNead');

            Route::get('getNeadEdit/{id}', 'Api\Web\NeadController@getNeadEdit');

            Route::post('store_nead', 'Api\Web\NeadController@store_nead');

            Route::delete('delete_nead/{id}', 'Api\Web\NeadController@delete_nead');
            Route::post('update_nead', 'Api\Web\NeadController@update_nead');


            //abmid

            Route::get('listAbmids', 'Api\Web\AbmidController@get_abmids');
            Route::get('getDadosAbmid/', 'Api\Web\AbmidController@getDadosAbmid');

            Route::get('getAbmidEdit/{id}', 'Api\Web\AbmidController@getAbmidEdit');

            Route::post('store_abmid', 'Api\Web\AbmidController@store_abmid');

            Route::delete('delete_abmid/{id}', 'Api\Web\AbmidController@delete_abmid');
            Route::post('update_abmid', 'Api\Web\AbmidController@update_abmid');



            //diagnosticos

            Route::get('listDiagnosticos', 'Api\Web\DiagnosticosPrimarioController@listDiagnosticos');
            Route::post('storeDiagnostico', 'Api\Web\DiagnosticosPrimarioController@store_diagnostico');
            Route::put('updateDiagnostico', 'Api\Web\DiagnosticosPrimarioController@update_diagnostico');

            Route::get('getDiagnostico/{id}', 'Api\Web\DiagnosticosPrimarioController@getDiagnostico');

            Route::delete('delete_diagnostico/{id}', 'Api\Web\DiagnosticosPrimarioController@delete_diagnostico');



            Route::get('listDiagnosticosSecundarios', 'Api\Web\DiagnosticosSecundarioController@listDiagnosticos');
            Route::post('storeDiagnosticoSecundario', 'Api\Web\DiagnosticosSecundarioController@store_diagnostico');
            Route::put('updateDiagnosticoSecundario', 'Api\Web\DiagnosticosSecundarioController@update_diagnostico');

            Route::get('getDiagnosticoSecundario/{id}', 'Api\Web\DiagnosticosSecundarioController@getDiagnostico');

            Route::delete('delete_diagnostico_secundario/{id}', 'Api\Web\DiagnosticosSecundarioController@delete_diagnostico');

        });

        Route::prefix('areaClinica')->group(function () {
            Route::get('dashboard/relatorioDiario', 'Api\Web\AreaClinica\DashboardController@relatorioDiario');
            Route::get('dashboard/relatorioProdutividade', 'Api\Web\AreaClinica\DashboardController@relatorioProdutividade');
            Route::get('dashboard/relatorioMedicamento', 'Api\Web\AreaClinica\DashboardController@relatorioMedicamentos');
            Route::get('dashboard/dashboardTotalProfissionaisEscalasPorPeriodo', 'Api\Web\AreaClinica\DashboardController@dashboardTotalProfissionaisEscalasPorPeriodo');
            Route::get('dashboard/dashboardTotalPacientesAtivosPorPeriodo', 'Api\Web\AreaClinica\DashboardController@dashboardTotalPacientesAtivosPorPeriodo');
            Route::get('dashboard/dashboarTotalContratosDesativadosPorPeriodo', 'Api\Web\AreaClinica\DashboardController@dashboarTotalContratosDesativadosPorPeriodo');
            Route::get('dashboard/dashboardTotalPacientesServicosPorPeriodo', 'Api\Web\AreaClinica\DashboardController@dashboardTotalPacientesServicosPorPeriodo');
            Route::get('dashboard/dashboardTotalRelatoriosPorPeriodo', 'Api\Web\AreaClinica\DashboardController@dashboardTotalRelatoriosPorPeriodo');
            Route::get('dashboard/dashboardTotalProfissionaisCategoriaPorPeriodo', 'Api\Web\AreaClinica\DashboardController@dashboardTotalProfissionaisCategoriaPorPeriodo');
            Route::get('dashboard/dashboarTotalPacientesPorSupervisor', 'Api\Web\AreaClinica\DashboardController@dashboarTotalPacientesPorSupervisor');
            Route::get('dashboard/dashboarTotalPacientesPorConvenio', 'Api\Web\AreaClinica\DashboardController@dashboarTotalPacientesPorConvenio');
            Route::get('dashboard/dashboarPorIdadePacientes', 'Api\Web\AreaClinica\DashboardController@dashboarPorIdadePacientes');
            Route::get('dashboard/dashboarCidadesMaisAtendidas', 'Api\Web\AreaClinica\DashboardController@dashboarCidadesMaisAtendidas');
            Route::get('dashboard/dashboarFaltasdeProfissionaisPorOperadora', 'Api\Web\AreaClinica\DashboardController@dashboarFaltasdeProfissionaisPorOperadora');
            Route::get('dashboard/dashboarFaltasdeProfissionaisPorEspecialidade', 'Api\Web\AreaClinica\DashboardController@dashboarFaltasdeProfissionaisPorEspecialidade');
            Route::get('dashboard/dashboarFaltasdeProfissionaisPorCidades', 'Api\Web\AreaClinica\DashboardController@dashboarFaltasdeProfissionaisPorCidades');
            Route::get('dashboard/dashboarTotalCheckinCheckout', 'Api\Web\AreaClinica\DashboardController@dashboarTotalCheckinCheckout');
            Route::get('dashboard/dashboardTotalMedicamentos', 'Api\Web\AreaClinica\DashboardController@dashboardTotalMedicamentos');
            Route::get('dashboard/dashboardTotalAtividades', 'Api\Web\AreaClinica\DashboardController@dashboardTotalAtividades');
        });
        Route::prefix('gestaoOrcamentaria')->group(function () {
            Route::get('dashboard/dashboarContratosPorPoriodo', 'Api\Web\GestaoOrcamentaria\DashboardController@dashboarContratosPorPoriodo');
        });
        Route::prefix('recursosHumanos')->group(function () {
            Route::get('dashboard/dashboardProfissionaisExternos', 'Api\Web\RecursosHumanos\DashboardController@dashboardProfissionaisExternos');
            Route::get('dashboard/dashboardMapaPacientesPorEspecialidade', 'Api\Web\RecursosHumanos\DashboardController@dashboardMapaPacientesPorEspecialidade');
        });
        Route::prefix('responsavel')->group(function () {
            Route::get('dashboard/relatorioDiario', 'Api\Web\Responsavel\DashboardController@relatorioDiario');
            Route::get('dashboard/relatorioProdutividade', 'Api\Web\Responsavel\DashboardController@relatorioProdutividade');
        });
        Route::get('buscaProfissionaisInternosPagamento', 'Api\Web\RecursosHumanos\ProfissionaisController@buscaProfissionaisInternosPagamento');
        Route::get('meu-perfil', 'Api\Web\RecursosHumanos\ProfissionaisController@meuperfil');
        Route::put('meu-perfil/atualizarFotoPerfil', 'Api\Web\RecursosHumanos\ProfissionaisController@atualizarFotoPerfil');

        Route::get('escalas/dashboard', 'Api\Web\EscalasController@dashboard');
    });
});

// Route::get('getEscalasHoje', 'Api\EscalasController@getEscalasHoje')->middleware('auth:api');
// Route::get('prestadores/atribuicao', 'Api\Web\PrestadoresController@buscaprestadoresporfiltro'); // MUDAR AQUII DEPOIS

/*------------------------------------------- Novas Rotas -------------------------------------------*/

/*------------- Rotas Utilizando Token -------------*/
Route::group(['middleware' => 'auth:api'], function () {
    /*----------------- Web -----------------*/
    Route::prefix('web')->group(function () {
        Route::prefix('prestadores')->group(function () {
            Route::get('listaComFiltro', [PrestadoresController::class, 'listaDePrestadoresComFiltro']);
        });
        Route::prefix('transcricaoProduto')->group(function () {
            Route::get('listaNaoRealizadosComFiltro', [TranscricaoProdutoController::class, 'listaDeMedicamentosNaoRealizadosComFiltro']);
        });
        Route::prefix('escalas/{escala}')->group(function () {
            Route::put('substituirPrestador', [EscalasController::class, 'substituirPrestador']);
        });
    });
});
