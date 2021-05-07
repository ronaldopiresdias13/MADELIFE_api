<?php

use App\Http\Controllers\Api\EmpresaPrestadorController as ApiEmpresaPrestadorController;
use App\Http\Controllers\Api\Novo\Web\EscalasController;
use App\Http\Controllers\Api\Novo\Web\OrdemservicoAcessoController;
use App\Http\Controllers\Api\Novo\Web\PrestadoresController;
use App\Http\Controllers\Api\Novo\Web\TranscricaoProdutoController;
use App\Http\Controllers\Api\Web\Agendamento\AgendamentosController;
use App\Http\Controllers\Api\Web\Compras\ProdutoController;
use App\Http\Controllers\Api\Web\DepartamentoPessoal\PagamentoexternosController;
use App\Http\Controllers\Api\Web\Financeiro\PagamentosCnabController;
use App\Http\Controllers\Api\Web\GestaoOrcamentaria\PacotesController;
use App\Http\Controllers\Web\Contratos\ContratosController;
use App\Http\Controllers\Web\Escalas\EscalasController as EscalasEscalasController;
use App\Http\Controllers\Web\Formacoes\FormacoesController;
use App\Http\Controllers\Web\Orcs\OrcsController;
use App\Http\Controllers\Web\Ordemservicos\OrdemservicosController;
use App\Http\Controllers\Web\PagamentointernosController;
use App\Http\Controllers\Web\Prestadores\PrestadoresController as PrestadoresPrestadoresController;
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
Route::get('prestadores/atribuicao', 'Api\Web\PrestadoresController@buscaprestadoresporfiltro'); // MUDAR AQUII DEPOIS






// Route::delete('transcricaoprodutos/{transcricao_produto}', 'Api\TranscricaoProdutoController@destroy');

// Route::get('unidademedidas', 'Api\UnidademedidasController@index');
// Route::post('unidademedidas', 'Api\UnidademedidasController@store');
// Route::get('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@show');
// Route::put('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@update');
// Route::delete('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@destroy');

// Route::get('users', 'Api\UsersController@index');
// Route::post('users', 'Api\UsersController@store');
// Route::get('users/{user}', 'Api\UsersController@show');
// Route::put('users/{user}', 'Api\UsersController@update');
// Route::put('users/{user}/updateEmail', 'Api\UsersController@updateEmail');
// Route::delete('users/{user}', 'Api\UsersController@destroy');

// Route::get('userAcessos', 'Api\UserAcessoController@index');
// Route::post('userAcessos', 'Api\UserAcessoController@store');
// Route::get('userAcessos/{userAcesso}', 'Api\UserAcessoController@show');
// Route::put('userAcessos/{userAcesso}', 'Api\UserAcessoController@update');
// Route::delete('userAcessos/{userAcesso}', 'Api\UserAcessoController@destroy');

// Route::get('vendas', 'Api\VendasController@index');
// Route::post('vendas', 'Api\VendasController@store');
// Route::get('vendas/{venda}', 'Api\VendasController@show');
// Route::put('vendas/{venda}', 'Api\VendasController@update');
// Route::delete('vendas/{venda}', 'Api\VendasController@destroy');
// Route::post('vendas/cadastrarCliente', 'Api\VendasController@cadastrarCliente');





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
