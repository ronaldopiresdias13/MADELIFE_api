<?php

use App\Http\Controllers\Api\Novo\Web\EscalasController;
use App\Http\Controllers\Api\Novo\Web\OrdemservicoAcessoController;
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

//-------------- Rota de Testes --------------//
// Route::group(['middleware' => 'auth:api'], function () {
Route::get("/teste", "Teste@teste");
// });

/*-------------- Rota de Logs por email --------------*/
Route::post("sendMailLog", "LogsController@sendMailLog");

/*-------------- Auth Web --------------*/
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Auth\AuthController@login')->name('login');
    Route::post('register', 'Auth\AuthController@register');
    Route::post('reset', 'Auth\AuthController@reset');
    Route::post('change', 'Auth\AuthController@change');

    /*------------- Rotas Utilizando Token -------------*/
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'Auth\AuthController@logout');
        Route::get('user', 'Auth\AuthController@user');
    });
});

/*-------------- Auth App --------------*/
Route::group([
    'prefix' => 'app/auth'
], function () {
    Route::post('login', 'Api\App\Auth\AuthController@login');
    Route::post('register', 'Api\App\Auth\AuthController@register');
    Route::post('reset', 'Api\App\Auth\AuthController@reset');

    /* ------------- Rotas Utilizando Token -------------*/
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('change', 'Api\App\Auth\AuthController@change');
        Route::get('logout', 'Api\App\Auth\AuthController@logout');
        // Route::get('user', 'Auth\AuthController@user');
    });
});

Route::group([
    'prefix' => 'app/v3_0_20/auth'
], function () {
    Route::post('login', 'Api\App\v3_0_20\Auth\AuthController@login');
    Route::post('register', 'Api\App\v3_0_20\Auth\AuthController@register');
    Route::post('reset', 'Api\App\v3_0_20\Auth\AuthController@reset');

    /* ------------- Rotas Utilizando Token -------------*/
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('change', 'Api\App\v3_0_20\Auth\AuthController@change');
        Route::get('logout', 'Api\App\v3_0_20\Auth\AuthController@logout');
        // Route::get('user', 'Auth\AuthController@user');
    });
});

/*------------- Rotas Utilizando Token -------------*/
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('getEscalasMesApp', 'Api\EscalasController@getEscalasMesApp');    // Mudar App e Apagar essa rota

    /*----------------- App -----------------*/
    Route::prefix('app')->group(function () {
        Route::post('acaomedicamentos', 'Api\App\AcaomedicamentosController@store');
        Route::get('bancos', 'Api\App\BancosController@index');

        Route::get('cidades/{uf}', 'Api\App\CidadeController@index');

        Route::post('conselhos', 'Api\App\ConselhosController@store');
        Route::put('conselhos/{conselho}', 'Api\App\ConselhosController@update');
        Route::delete('conselhos/{conselho}', 'Api\App\ConselhosController@destroy');

        Route::post('dadosbancarios', 'Api\App\DadosbancariosController@store');
        Route::put('dadosbancarios/{dadosbancario}', 'Api\App\DadosbancariosController@update');
        Route::delete('dadosbancarios/{dadosbancario}', 'Api\App\DadosbancariosController@destroy');

        Route::get('empresaPrestador/meusContratos', 'Api\App\EmpresaPrestadorController@index');
        Route::put('empresaPrestador/meusContratos/{empresaPrestador}', 'Api\App\EmpresaPrestadorController@update');
        Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\App\EmpresaPrestadorController@downloadFile');


        Route::get('escalas/listEscalasHoje', 'Api\App\EscalasController@listEscalasHoje');
        Route::get('escalas/listEscalasMes', 'Api\App\EscalasController@listEscalasMes');
        Route::get('escalas/getEscalaId/{escala}', 'Api\App\EscalasController@getEscalaId');
        Route::get('escalas/getEscalaId/{escala}/cuidados', 'Api\App\EscalasController@getCuidadosByEscalaId');

        Route::put('cuidadoEscalas/{cuidadoEscala}', 'Api\App\CuidadoEscalasController@updateCuidado');

        Route::get('formacoes/listFormacoes', 'Api\App\FormacoesController@listFormacoes');

        Route::get('monitoramentoescalas/{escala}', 'Api\App\MonitoramentoescalasController@listaMonitoramento');
        Route::post('monitoramentoescalas', 'Api\App\MonitoramentoescalasController@salvarMonitoramento');

        Route::post('prestadorFormacao/newPrestadorFormacao', 'Api\App\PrestadorFormacaoController@newPrestadorFormacao');

        Route::get('pessoas/getPessoaPerfil', 'Api\App\PessoasController@getPessoaPerfil');
        Route::put('pessoas/atualizaDadosPessoais/{pessoa}', 'Api\App\PessoasController@atualizaDadosPessoais');

        Route::post('pessoaTelefones', 'Api\App\PessoaTelefoneController@store');
        Route::delete('pessoaTelefones/{pessoaTelefone}', 'Api\PessoaTelefoneController@destroy');

        Route::post('pessoaEmails', 'Api\App\PessoaEmailController@store');
        Route::delete('pessoaEmails/{pessoaEmail}', 'Api\App\PessoaEmailController@destroy');

        Route::post('pessoaEnderecos', 'Api\App\PessoaEnderecoController@store');
        Route::put('pessoaEnderecos/{pessoaEndereco}', 'Api\App\PessoaEnderecoController@update');
        Route::delete('pessoaEnderecos/{pessoaEndereco}', 'Api\App\PessoaEnderecoController@destroy');

        Route::post('pontos', 'Api\PontosController@store');
        Route::post('pontos/checkin/{escala}', 'Api\App\PontosController@checkin'); // Custon
        Route::post('pontos/checkout/{escala}', 'Api\App\PontosController@checkout'); // Custon
        Route::post('pontos/assinaturacheckout/{escala}', 'Api\App\PontosController@assinaturacheckout'); // Custon

        Route::get('prestadores/meuspacientes', 'Api\App\PrestadoresController@meuspacientes'); // Custon

        Route::get('relatorios/{escala}', 'Api\App\RelatoriosController@listRelatoriosByEscalaId');
        Route::get('relatorios/{escala}/list', 'Api\App\RelatoriosController@getAllRelatoriosByEscalaId');
        Route::post('relatorios', 'Api\App\RelatoriosController@store');
        Route::delete('relatorios/{relatorio}', 'Api\App\RelatoriosController@destroy');

        Route::get('transcricoes/{ordemservico}', 'Api\App\TranscricoesController@listTranscricoesByEscalaId');
    });

    Route::prefix("app/v3_0_20")->group(function () {
        Route::post('acaomedicamentos', 'Api\App\v3_0_20\AcaomedicamentosController@store');

        Route::get('bancos', 'Api\App\v3_0_20\BancosController@index');

        Route::get('cidades/{uf}', 'Api\App\v3_0_20\CidadeController@index');

        Route::post('conselhos', 'Api\App\v3_0_20\ConselhosController@store');
        Route::put('conselhos/{conselho}', 'Api\App\v3_0_20\ConselhosController@update');
        Route::delete('conselhos/{conselho}', 'Api\App\v3_0_20\ConselhosController@destroy');

        Route::post('dadosbancarios', 'Api\App\v3_0_20\DadosbancariosController@store');
        Route::put('dadosbancarios/{dadosbancario}', 'Api\App\v3_0_20\DadosbancariosController@update');
        Route::delete('dadosbancarios/{dadosbancario}', 'Api\App\v3_0_20\DadosbancariosController@destroy');

        Route::get('empresaPrestador/meusContratos', 'Api\App\v3_0_20\EmpresaPrestadorController@index');
        Route::put('empresaPrestador/meusContratos/{empresaPrestador}', 'Api\App\v3_0_20\EmpresaPrestadorController@update');
        Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\App\v3_0_20\EmpresaPrestadorController@downloadFile');


        Route::get('escalas/listEscalasHoje', 'Api\App\v3_0_20\EscalasController@listEscalasHoje');
        Route::get('escalas/listEscalasMes', 'Api\App\v3_0_20\EscalasController@listEscalasMes');
        Route::get('escalas/getEscalaId/{escala}', 'Api\App\v3_0_20\EscalasController@getEscalaId');
        Route::get('escalas/getEscalaId/{escala}/cuidados', 'Api\App\v3_0_20\EscalasController@getCuidadosByEscalaId');

        Route::put('cuidadoEscalas/{cuidadoEscala}', 'Api\App\v3_0_20\CuidadoEscalasController@updateCuidado');

        Route::get('formacoes/listFormacoes', 'Api\App\v3_0_20\FormacoesController@listFormacoes');

        Route::get('monitoramentoescalas/{escala}', 'Api\App\v3_0_20\MonitoramentoescalasController@listaMonitoramento');
        Route::post('monitoramentoescalas', 'Api\App\v3_0_20\MonitoramentoescalasController@salvarMonitoramento');

        Route::post('prestadorFormacao/newPrestadorFormacao', 'Api\App\v3_0_20\PrestadorFormacaoController@newPrestadorFormacao');
        Route::delete('prestadorFormacao/{prestadorFormacao}', 'Api\App\v3_0_20\PrestadorFormacaoController@destroy');

        Route::get('pessoas/getPessoaPerfil', 'Api\App\v3_0_20\PessoasController@getPessoaPerfil');
        Route::put('pessoas/atualizaDadosPessoais/{pessoa}', 'Api\App\v3_0_20\PessoasController@atualizaDadosPessoais');

        Route::post('pessoaTelefones', 'Api\App\v3_0_20\PessoaTelefoneController@store');
        // Route::delete('pessoaTelefones/{pessoaTelefone}', 'Api\PessoaTelefoneController@destroy');

        Route::post('pessoaEmails', 'Api\App\v3_0_20\PessoaEmailController@store');
        Route::delete('pessoaEmails/{pessoaEmail}', 'Api\App\v3_0_20\PessoaEmailController@destroy');

        Route::post('pessoaEnderecos', 'Api\App\v3_0_20\PessoaEnderecoController@store');
        Route::put('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_0_20\PessoaEnderecoController@update');
        Route::delete('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_0_20\PessoaEnderecoController@destroy');

        // Route::post('pontos', 'Api\PontosController@store');
        Route::post('pontos/checkin/{escala}', 'Api\App\v3_0_20\PontosController@checkin'); // Custon
        Route::post('pontos/checkout/{escala}', 'Api\App\v3_0_20\PontosController@checkout'); // Custon
        Route::post('pontos/assinaturacheckout/{escala}', 'Api\App\v3_0_20\PontosController@assinaturacheckout'); // Custon

        Route::get('prestadores/meuspacientes', 'Api\App\v3_0_20\PrestadoresController@meuspacientes'); // Custon

        Route::get('relatorios/{escala}', 'Api\App\v3_0_20\RelatoriosController@listRelatoriosByEscalaId');
        Route::get('relatorios/{escala}/list', 'Api\App\v3_0_20\RelatoriosController@getAllRelatoriosByEscalaId');
        Route::post('relatorios', 'Api\App\v3_0_20\RelatoriosController@store');
        Route::delete('relatorios/{relatorio}', 'Api\App\v3_0_20\RelatoriosController@destroy');

        Route::get('transcricoes/{ordemservico}', 'Api\App\v3_0_20\TranscricoesController@listTranscricoesByEscalaId');
    });

    /*----------------- Web -----------------*/
    Route::prefix('web')->group(function () {
        Route::prefix('areaClinica')->group(function () {
            Route::get('documentos/listDocumentos', 'Api\Web\AreaClinica\DocumentosController@listDocumentos');
            Route::delete('documentos/{documento}', 'Api\Web\AreaClinica\DocumentosController@destroy');
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
            Route::get('profissionais/historicopacientesprestador/{prestador}', 'Api\Web\PrestadoresController@historicopacientesprestador');
        });
        Route::prefix('convenio')->group(function () {
            Route::get('categoriadocumentos/listCategorias', 'Api\Web\Convenio\CategoriadocumentosController@listCategorias');
            Route::get('escalas/dashboard', 'Api\Web\Convenio\EscalasController@dashboardConvenio');
            Route::get('escalas/listEscalasByIdCliente', 'Api\Web\Convenio\EscalasController@listEscalasByIdCliente');
            Route::get('dashboard/relatorioDiario', 'Api\Web\Convenio\EscalasController@relatorioDiario');
            Route::get('dashboard/relatorioProdutividade', 'Api\Web\Convenio\EscalasController@relatorioProdutividade');
            Route::get('escalas/dashboardPegarTodosOsRegistrosPorIdDaEmpresa', 'Api\Web\Convenio\EscalasController@dashboardConvenio');
        });
        Route::prefix('diretoria')->group(function () {
            Route::get('groupByPagamentoByMesAndEmpresaId', 'Api\Web\Financeiro\PagamentopessoasController@groupByPagamentoByMesAndEmpresaId');
            Route::post('atualizarSituacaoPagamentoDiretoria', 'Api\Web\Financeiro\PagamentopessoasController@atualizarSituacaoPagamentoDiretoria');
        });
        Route::prefix('financeiro')->group(function () {
            Route::get('listPagamentosByEmpresaId', 'Api\Web\Financeiro\PagamentopessoasController@listPagamentosByEmpresaId');
        });
        Route::prefix('gestaoOrcamentaria')->group(function () {
            Route::get('clientes/list/{empresa}', 'Api\Web\GestaoOrcamentaria\ClientesController@index');
            Route::get('clientes/{cliente}', 'Api\Web\GestaoOrcamentaria\ClientesController@show');
            Route::post('clientes', 'Api\Web\GestaoOrcamentaria\ClientesController@store');
            Route::put('clientes/{cliente}', 'Api\Web\GestaoOrcamentaria\ClientesController@update');
            Route::delete('clientes/{cliente}', 'Api\Web\GestaoOrcamentaria\ClientesController@destroy');

            Route::get('orcamentos/list/{empresa}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@index');
            Route::get('orcamentos/{orcamento}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@show');
            Route::post('orcamentos', 'Api\Web\GestaoOrcamentaria\OrcamentosController@store');
            Route::put('orcamentos/{orcamento}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@update');
            Route::delete('orcamentos/{orcamento}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@destroy');

            Route::get('contratos/dashboardGroupByMotivoDesativados', 'Api\Web\OrdemservicosController@dashboardGroupByMotivoDesativados');
            Route::get('contratos/dashboardGroupByStatusAtivadosDesativados', 'Api\Web\OrdemservicosController@dashboardGroupByStatusAtivadosDesativados');

            Route::get('pacientes/list/{empresa}', 'Api\Web\PacientesController@index');
            Route::get('pacientes/{paciente}', 'Api\Web\PacientesController@show');
            Route::post('pacientes', 'Api\Web\PacientesController@store');
            Route::put('pacientes/{paciente}', 'Api\Web\PacientesController@update');
            Route::delete('pacientes/{paciente}', 'Api\Web\PacientesController@destroy');

            Route::get('responsaveis/list/{empresa}', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@index');
            Route::get('responsaveis/{responsavel}', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@show');
            Route::post('responsaveis', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@store');
            Route::put('responsaveis/{responsavel}', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@update');
            Route::delete('responsaveis/{responsavel}', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@destroy');


            Route::get('servicos/list/{empresa}', 'Api\Web\GestaoOrcamentaria\ServicosController@index');
            Route::get('servicos/listServicosFormacoes', 'Api\Web\GestaoOrcamentaria\ServicosController@listServicosFormacoes');
            Route::post('servicos', 'Api\Web\GestaoOrcamentaria\ServicosController@store');
            Route::get('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@show');
            Route::put('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@update');
            Route::delete('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@destroy');
        });

        Route::prefix('departamentoPessoal')->group(function () {
            Route::get('pontos/pontosPrestadores', 'Api\Web\DepartamentoPessoal\PontosController@pontosPrestadores');
            Route::post('escalas/updateServicoOfEscala/{escala}', 'Api\Web\DepartamentoPessoal\EscalasController@updateServicoOfEscala');
        });

        Route::prefix('recursosHumanos')->group(function () {
            Route::post('profissionais/novoProfissional', 'Api\Web\RecursosHumanos\ProfissionaisController@novoProfissional');
        });

        // novoProfissional

        Route::prefix('responsavel')->group(function () {
            Route::get('escalas/listEscalasByIdResponsavel', 'Api\Web\Responsavel\EscalasController@listEscalasByIdResponsavel');
            Route::get('escalas/listEscalasByIdOrdemServico/{ordemservico}', 'Api\Web\Responsavel\EscalasController@listEscalasByIdOrdemServico');
            Route::post('escalas/assinar', 'Api\Web\Responsavel\EscalasController@assinar');
            Route::get('escalas/dashboard', 'Api\Web\Responsavel\EscalasController@dashboard');
            Route::get('escalas/dashboardPegarTodosOsRegistrosPorIdDaEmpresa', 'Api\Web\Responsavel\EscalasController@dashboardPegarTodosOsRegistrosPorIdDaEmpresa');
            Route::get('dashboard/relatorioDiario', 'Api\Web\Responsavel\DashboardController@relatorioDiario');
            Route::get('dashboard/relatorioProdutividade', 'Api\Web\Responsavel\DashboardController@relatorioProdutividade');
        });

        Route::get('categoriadocumentos/listCategorias', 'Api\Web\CategoriadocumentosController@listCategorias');
        Route::post('categoriadocumentos/newCategoria', 'Api\Web\CategoriadocumentosController@newCategoria');

        Route::get('documentos/listDocumentosByEmpresa', 'Api\Web\DocumentosController@listDocumentosByEmpresa');
        Route::get('documentos/listDocumentosByConvenio', 'Api\Web\DocumentosController@listDocumentosByConvenio');
        Route::get('documentos/listDocumentos', 'Api\Web\DocumentosController@listDocumentos');
        Route::post('documentos/newDocumento', 'Api\Web\DocumentosController@newDocumento');
        Route::get('documentos/download/{documento}', 'Api\Web\DocumentosController@download');
        Route::delete('documentos/delete/{documento}', 'Api\Web\DocumentosController@delete');

        Route::get('escalas/dashboard', 'Api\Web\EscalasController@dashboard');

        Route::get('prestadores/listNomePrestadores', 'Api\Web\PrestadoresController@listNomePrestadores');
        Route::get('prestadores/listPrestadoresComFormacoes', 'Api\Web\PrestadoresController@listPrestadoresComFormacoes');

        Route::get('pacientes/listNomePacientes', 'Api\Web\PacientesController@listNomePacientes');

        Route::get('ordemservicos/listaOrdemServicosEscalas', 'Api\Web\OrdemservicosController@listaOrdemServicosEscalas');
    });


    Route::get('ordemservicos/listaOrdemServicosEscalas', 'Api\OrdemservicosController@listaOrdemServicosEscalas');
    Route::get('transcricoes/listaTranscricoes', 'Api\TranscricoesController@listaTranscricoes');
});

// Route::get('getEscalasHoje', 'Api\EscalasController@getEscalasHoje')->middleware('auth:api');
Route::get('prestadores/atribuicao', 'Api\Web\PrestadoresController@buscaprestadoresporfiltro'); // MUDAR AQUII DEPOIS

Route::get('acaomedicamentos', 'Api\AcaomedicamentosController@index');
Route::post('acaomedicamentos', 'Api\AcaomedicamentosController@store');
Route::get('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@show');
Route::put('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@update');
Route::delete('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@destroy');

Route::get('acessos', 'Api\AcessosController@index');
Route::post('acessos', 'Api\AcessosController@store');
Route::get('acessos/{acesso}', 'Api\AcessosController@show');
Route::put('acessos/{acesso}', 'Api\AcessosController@update');
Route::delete('acessos/{acesso}', 'Api\AcessosController@destroy');

Route::get('agendamentos', 'Api\AgendamentosController@index');
Route::post('agendamentos', 'Api\AgendamentosController@store');
Route::get('agendamentos/{agendamento}', 'Api\AgendamentosController@show');
Route::put('agendamentos/{agendamento}', 'Api\AgendamentosController@update');
Route::delete('agendamentos/{agendamento}', 'Api\AgendamentosController@destroy');

Route::get('bancos', 'Api\BancosController@index');
Route::post('bancos', 'Api\BancosController@store');
Route::get('bancos/{banco}', 'Api\BancosController@show');
Route::put('bancos/{banco}', 'Api\BancosController@update');
Route::delete('bancos/{banco}', 'Api\BancosController@destroy');

Route::get('beneficios', 'Api\BeneficiosController@index');
Route::post('beneficios', 'Api\BeneficiosController@store');
Route::get('beneficios/{beneficio}', 'Api\BeneficiosController@show');
Route::put('beneficios/{beneficio}', 'Api\BeneficiosController@update');
Route::delete('beneficios/{beneficio}', 'Api\BeneficiosController@destroy');

Route::get('cargos', 'Api\CargosController@index');
Route::post('cargos', 'Api\CargosController@store');
Route::get('cargos/{cargo}', 'Api\CargosController@show');
Route::put('cargos/{cargo}', 'Api\CargosController@update');
Route::delete('cargos/{cargo}', 'Api\CargosController@destroy');

Route::get('categorianaturezas', 'Api\CategorianaturezasController@index');
Route::post('categorianaturezas', 'Api\CategorianaturezasController@store');
Route::get('categorianaturezas/{categorianatureza}', 'Api\CategorianaturezasController@show');
Route::put('categorianaturezas/{categorianatureza}', 'Api\CategorianaturezasController@update');
Route::delete('categorianaturezas/{categorianatureza}', 'Api\CategorianaturezasController@destroy');

Route::get('cidades', 'Api\CidadesController@index');
Route::post('cidades', 'Api\CidadesController@store');
Route::get('cidades/{cidade}', 'Api\CidadesController@show');
Route::put('cidades/{cidade}', 'Api\CidadesController@update');
Route::delete('cidades/{cidade}', 'Api\CidadesController@destroy');

Route::get('certificadoprestadores', 'Api\CertificadoprestadoresController@index');
Route::post('certificadoprestadores', 'Api\CertificadoprestadoresController@store');
Route::get('certificadoprestadores/{certificadoprestador}', 'Api\CertificadoprestadoresController@show');
Route::put('certificadoprestadores/{certificadoprestador}', 'Api\CertificadoprestadoresController@update');
Route::delete('certificadoprestadores/{certificadoprestador}', 'Api\CertificadoprestadoresController@destroy');

Route::get('clientes', 'Api\ClientesController@index');
Route::post('clientes', 'Api\ClientesController@store');
Route::get('clientes/{cliente}', 'Api\ClientesController@show');
Route::put('clientes/{cliente}', 'Api\ClientesController@update');
Route::delete('clientes/{cliente}', 'Api\ClientesController@destroy');
// Route::get('meuspassientes/{cliente}', 'Api\ClientesController@meuspassientes'); // Custon

Route::get('cnabs', 'Api\CnabsController@index');
Route::post('cnabs', 'Api\CnabsController@store');
Route::get('cnabs/{cnab}/{tipo}', 'Api\CnabsController@show');
Route::put('cnabs/{cnab}', 'Api\CnabsController@update');
Route::delete('cnabs/{cnab}', 'Api\CnabsController@destroy');

Route::get('conselhos', 'Api\ConselhosController@index');
Route::post('conselhos', 'Api\ConselhosController@store');
Route::get('conselhos/{conselho}', 'Api\ConselhosController@show');
Route::put('conselhos/{conselho}', 'Api\ConselhosController@update');
Route::delete('conselhos/{conselho}', 'Api\ConselhosController@destroy');

Route::get('contas', 'Api\ContasController@index');
Route::post('contas', 'Api\ContasController@store');
Route::get('contas/{contas}', 'Api\ContasController@show');
Route::put('contas/{contas}', 'Api\ContasController@update');
Route::delete('contas/{contas}', 'Api\ContasController@destroy');

Route::get('contasbancarias', 'Api\ContasbancariasController@index');
Route::post('contasbancarias', 'Api\ContasbancariasController@store');
Route::get('contasbancarias/{contasbancaria}', 'Api\ContasbancariasController@show');
Route::put('contasbancarias/{contasbancaria}', 'Api\ContasbancariasController@update');
Route::delete('contasbancarias/{contasbancaria}', 'Api\ContasbancariasController@destroy');

Route::get('convenios', 'Api\ConveniosController@index');
Route::post('convenios', 'Api\ConveniosController@store');
Route::get('convenios/{convenio}', 'Api\ConveniosController@show');
Route::put('convenios/{convenio}', 'Api\ConveniosController@update');
Route::delete('convenios/{convenio}', 'Api\ConveniosController@destroy');

Route::get('cotacoes', 'Api\CotacoesController@index');
Route::post('cotacoes', 'Api\CotacoesController@store');
Route::get('cotacoes/{cotacao}', 'Api\CotacoesController@show');
Route::put('cotacoes/{cotacao}', 'Api\CotacoesController@update');
Route::delete('cotacoes/{cotacao}', 'Api\CotacoesController@destroy');

Route::get('cotacaoproduto', 'Api\CotacaoProdutoController@index');
Route::post('cotacaoproduto', 'Api\CotacaoProdutoController@store');
Route::get('cotacaoproduto/{cotacaoproduto}', 'Api\CotacaoProdutoController@show');
Route::put('cotacaoproduto/{cotacaoproduto}', 'Api\CotacaoProdutoController@update');
Route::delete('cotacaoproduto/{cotacaoproduto}', 'Api\CotacaoProdutoController@destroy');

Route::get('cuidados', 'Api\CuidadosController@index');
Route::post('cuidados', 'Api\CuidadosController@store');
Route::get('cuidados/{cuidado}', 'Api\CuidadosController@show');
Route::put('cuidados/{cuidado}', 'Api\CuidadosController@update');
Route::delete('cuidados/{cuidado}', 'Api\CuidadosController@destroy');
Route::get('cuidados/count/{empresa}', 'Api\CuidadosController@quantidadecuidados');
Route::get('cuidados/empresa/{empresa}', 'Api\CuidadosController@indexbyempresa');

Route::get('cuidadoEscalas', 'Api\CuidadoEscalasController@index');
Route::post('cuidadoEscalas', 'Api\CuidadoEscalasController@store');
Route::get('cuidadoEscalas/{cuidadoEscala}', 'Api\CuidadoEscalasController@show');
Route::put('cuidadoEscalas/{cuidadoEscala}', 'Api\CuidadoEscalasController@update');
Route::delete('cuidadoEscalas/{cuidadoEscala}', 'Api\CuidadoEscalasController@destroy');

Route::get('cuidadoPacientes', 'Api\CuidadoPacienteController@index');
Route::post('cuidadoPacientes', 'Api\CuidadoPacienteController@store');
Route::get('cuidadoPacientes/{cuidadoPaciente}', 'Api\CuidadoPacienteController@show');
Route::put('cuidadoPacientes/{cuidadoPaciente}', 'Api\CuidadoPacienteController@update');
Route::delete('cuidadoPacientes/{cuidadoPaciente}', 'Api\CuidadoPacienteController@destroy');
Route::get('cuidadoPacientes/paciente/{paciente}', 'Api\CuidadoPacienteController@buscacuidadosdopaciente');
Route::get('cuidadoPacientes/groupby/{paciente}', 'Api\CuidadoPacienteController@groupbycuidadosdopaciente');

Route::get('dadosbancarios', 'Api\DadosbancariosController@index');
Route::post('dadosbancarios', 'Api\DadosbancariosController@store');
Route::get('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@show');
Route::put('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@update');
Route::delete('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@destroy');

Route::get('dadoscontratuais', 'Api\DadoscontratuaisController@index');
Route::post('dadoscontratuais', 'Api\DadoscontratuaisController@store');
Route::get('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@show');
Route::put('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@update');
Route::delete('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@destroy');

Route::get('diagnosticossecundarios', 'Api\DiagnosticossecundariosController@index');
Route::post('diagnosticossecundarios', 'Api\DiagnosticossecundariosController@store');
Route::get('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@show');
Route::put('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@update');
Route::delete('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@destroy');


Route::get('emails', 'Api\EmailsController@index');
Route::post('emails', 'Api\EmailsController@store');
Route::get('emails/{email}', 'Api\EmailsController@show');
Route::put('emails/{email}', 'Api\EmailsController@update');
Route::delete('emails/{email}', 'Api\EmailsController@destroy');

Route::get('empresas', 'Api\EmpresasController@index');
Route::post('empresas', 'Api\EmpresasController@store');
Route::get('empresas/{empresa}', 'Api\EmpresasController@show');
Route::put('empresas/{empresa}', 'Api\EmpresasController@update');
Route::delete('empresas/{empresa}', 'Api\EmpresasController@destroy');

Route::get('empresaPrestador', 'Api\EmpresaPrestadorController@index');
Route::post('empresaPrestador', 'Api\EmpresaPrestadorController@store');
Route::get('empresaPrestador/{empresaPrestador}', 'Api\EmpresaPrestadorController@show');
Route::put('empresaPrestador/{empresaPrestador}', 'Api\EmpresaPrestadorController@update');
Route::delete('empresaPrestador/{empresaPrestador}', 'Api\EmpresaPrestadorController@destroy');
Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\EmpresaPrestadorController@downloadFile');
Route::get('empresaPrestador/empresa/{empresa}', 'Api\EmpresaPrestadorController@indexbyempresa');
Route::get('empresaPrestador/count/{empresa}', 'Api\EmpresaPrestadorController@quantidadeempresaprestador');

Route::get('enderecos', 'Api\EnderecosController@index');
Route::post('enderecos', 'Api\EnderecosController@store');
Route::get('enderecos/{endereco}', 'Api\EnderecosController@show');
Route::put('enderecos/{endereco}', 'Api\EnderecosController@update');
Route::delete('enderecos/{endereco}', 'Api\EnderecosController@destroy');

Route::get('entradas', 'Api\EntradasController@index');
Route::post('entradas', 'Api\EntradasController@store');
Route::post('entradas/cadastrarFornecedor', 'Api\EntradasController@cadastrarFornecedor');
Route::get('entradas/{entrada}', 'Api\EntradasController@show');
Route::put('entradas/{entrada}', 'Api\EntradasController@update');
Route::delete('entradas/{entrada}', 'Api\EntradasController@destroy');

Route::get('escalas', 'Api\EscalasController@index');
Route::post('escalas', 'Api\EscalasController@store');
Route::get('escalas/{escala}', 'Api\EscalasController@show');
Route::put('escalas/{escala}', 'Api\EscalasController@update');
Route::delete('escalas/{escala}', 'Api\EscalasController@destroy');
Route::get('escalas/empresa/{empresa}/dia', 'Api\EscalasController@buscaescalasdodia');
Route::get('escalas/paciente/{paciente}/data1/{data1}/data2/{data2}', 'Api\EscalasController@buscaPontosPorPeriodoEPaciente');

Route::get('formacoes', 'Api\FormacoesController@index');
Route::post('formacoes', 'Api\FormacoesController@store');
Route::get('formacoes/{formacao}', 'Api\FormacoesController@show');
Route::put('formacoes/{formacao}', 'Api\FormacoesController@update');
Route::delete('formacoes/{formacao}', 'Api\FormacoesController@destroy');

Route::get('fornecedores', 'Api\FornecedoresController@index');
Route::post('fornecedores', 'Api\FornecedoresController@store');
Route::get('fornecedores/{fornecedor}', 'Api\FornecedoresController@show');
Route::put('fornecedores/{fornecedor}', 'Api\FornecedoresController@update');
Route::delete('fornecedores/{fornecedor}', 'Api\FornecedoresController@destroy');

Route::get('grupocuidados', 'Api\GrupocuidadosController@index');
Route::post('grupocuidados', 'Api\GrupocuidadosController@store');
Route::get('grupocuidados/{grupocuidado}', 'Api\GrupocuidadosController@show');
Route::put('grupocuidados/{grupocuidado}', 'Api\GrupocuidadosController@update');
Route::delete('grupocuidados/{grupocuidado}', 'Api\GrupocuidadosController@destroy');

Route::get('historicoorcamentos', 'Api\HistoricoorcamentosController@index');
Route::post('historicoorcamentos', 'Api\HistoricoorcamentosController@store');
Route::get('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@show');
Route::put('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@update');
Route::delete('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@destroy');

Route::get('horariostrabalho', 'Api\HorariostrabalhoController@index');
Route::post('horariostrabalho', 'Api\HorariostrabalhoController@store');
Route::get('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@show');
Route::put('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@update');
Route::delete('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@destroy');

Route::get('homecares', 'Api\HomecaresController@index');
Route::post('homecares', 'Api\HomecaresController@store');
Route::get('homecares/{homecare}', 'Api\HomecaresController@show');
Route::put('homecares/{homecare}', 'Api\HomecaresController@update');
Route::delete('homecares/{homecare}', 'Api\HomecaresController@destroy');

Route::get('impostos', 'Api\ImpostosController@index');
Route::post('impostos', 'Api\ImpostosController@store');
Route::get('impostos/{imposto}', 'Api\ImpostosController@show');
Route::put('impostos/{imposto}', 'Api\ImpostosController@update');
Route::delete('impostos/{imposto}', 'Api\ImpostosController@destroy');

Route::get('marcas', 'Api\MarcasController@index');
Route::post('marcas', 'Api\MarcasController@store');
Route::get('marcas/{marca}', 'Api\MarcasController@show');
Route::put('marcas/{marca}', 'Api\MarcasController@update');
Route::delete('marcas/{marca}', 'Api\MarcasController@destroy');

Route::get('medicoes', 'Api\MedicoesController@index');
Route::post('medicoes', 'Api\MedicoesController@store');
Route::get('medicoes/{medicao}', 'Api\MedicoesController@show');
Route::put('medicoes/{medicao}', 'Api\MedicoesController@update');
Route::delete('medicoes/{medicao}', 'Api\MedicoesController@destroy');

Route::get('monitoramentoescalas', 'Api\MonitoramentoescalasController@index');
Route::post('monitoramentoescalas', 'Api\MonitoramentoescalasController@store');
Route::get('monitoramentoescalas/{monitoramentoescala}', 'Api\MonitoramentoescalasController@show');
Route::put('monitoramentoescalas/{monitoramentoescala}', 'Api\MonitoramentoescalasController@update');
Route::delete('monitoramentoescalas/{monitoramentoescala}', 'Api\MonitoramentoescalasController@destroy');

Route::get('naturezas', 'Api\NaturezasController@index');
Route::post('naturezas', 'Api\NaturezasController@store');
Route::get('naturezas/{natureza}', 'Api\NaturezasController@show');
Route::put('naturezas/{natureza}', 'Api\NaturezasController@update');
Route::delete('naturezas/{natureza}', 'Api\NaturezasController@destroy');

Route::get('orcamentos', 'Api\OrcamentosController@index');
Route::post('orcamentos', 'Api\OrcamentosController@store');
Route::get('orcamentos/{orcamento}', 'Api\OrcamentosController@show');
Route::put('orcamentos/{orcamento}', 'Api\OrcamentosController@update');
Route::put('alterarsituacao/{orcamento}', 'Api\OrcamentosController@alterarSituacao');
Route::delete('orcamentos/{orcamento}', 'Api\OrcamentosController@destroy');

Route::get('orcamentocustos', 'Api\OrcamentoCustosController@index');
Route::post('orcamentocustos', 'Api\OrcamentoCustosController@store');
Route::get('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@show');
Route::put('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@update');
Route::delete('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@destroy');

Route::get('orcamentoprodutos', 'Api\OrcamentoProdutosController@index');
Route::post('orcamentoprodutos', 'Api\OrcamentoProdutosController@store');
Route::get('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@show');
Route::put('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@update');
Route::delete('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@destroy');

Route::get('orcamentoservicos', 'Api\OrcamentoServicosController@index');
Route::post('orcamentoservicos', 'Api\OrcamentoServicosController@store');
Route::get('orcamentoservicos/{orcamentoServico}', 'Api\OrcamentoServicosController@show');
Route::put('orcamentoservicos/{orcamentoServico}', 'Api\OrcamentoServicosController@update');
Route::delete('orcamentoservicos/{orcamentoServico}', 'Api\OrcamentoServicosController@destroy');

Route::get('ordemservicos', 'Api\OrdemservicosController@index');
Route::post('ordemservicos', 'Api\OrdemservicosController@store');
Route::get('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@show');
Route::put('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@update');
Route::delete('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@destroy');
Route::get(
    'ordemservicos/{ordemservico}/horariomedicamentos',
    'Api\OrdemservicosController@horariomedicamentos'
); // Custon
Route::get('ordemservicos/count/{empresa}', 'Api\OrdemservicosController@quantidadeordemservicos');
Route::get('ordemservicos/groupbyservico/{empresa}', 'Api\OrdemservicosController@groupbyservicos');

Route::get('ordemservicoServicos', 'Api\OrdemservicoServicoController@index');
Route::post('ordemservicoServicos', 'Api\OrdemservicoServicoController@store');
Route::get('ordemservicoServicos/{ordemservicoServico}', 'Api\OrdemservicoServicoController@show');
Route::put('ordemservicoServicos/{ordemservicoServico}', 'Api\OrdemservicoServicoController@update');
Route::delete('ordemservicoServicos/{ordemservicoServico}', 'Api\OrdemservicoServicoController@destroy');

Route::get('ordemservicoPrestadores', 'Api\OrdemservicoPrestadoresController@index');
Route::post('ordemservicoPrestadores', 'Api\OrdemservicoPrestadoresController@store');
Route::get('ordemservicoPrestadores/{ordemservicoPrestador}', 'Api\OrdemservicoPrestadoresController@show');
Route::put('ordemservicoPrestadores/{ordemservicoPrestador}', 'Api\OrdemservicoPrestadoresController@update');
Route::delete('ordemservicoPrestadores/{ordemservicoPrestador}', 'Api\OrdemservicoPrestadoresController@destroy');
Route::get('ordemservicoPrestadores/ordemservico/{ordemservico}', 'Api\OrdemservicoPrestadoresController@profissionaisatribuidosaopaciente');

Route::get('outros', 'Api\OutrosController@index');
Route::post('outros', 'Api\OutrosController@store');
Route::get('outros/{outro}', 'Api\OutrosController@show');
Route::put('outros/{outro}', 'Api\OutrosController@update');
Route::delete('outros/{outro}', 'Api\OutrosController@destroy');

Route::get('pacientes', 'Api\PacientesController@index');
Route::post('pacientes', 'Api\PacientesController@store');
Route::get('pacientes/{paciente}', 'Api\PacientesController@show');
Route::put('pacientes/{paciente}', 'Api\PacientesController@update');
Route::delete('pacientes/{paciente}', 'Api\PacientesController@destroy');

Route::get('pagamentos', 'Api\PagamentosController@index');
Route::post('pagamentos', 'Api\PagamentosController@store');
Route::get('pagamentos/{pagamento}', 'Api\PagamentosController@show');
Route::put('pagamentos/{pagamento}', 'Api\PagamentosController@update');
Route::delete('pagamentos/{pagamento}', 'Api\PagamentosController@destroy');
Route::get('pagamentosfiltro', 'Api\PagamentosController@filtro');                    // Custon

Route::get('pagamentopessoas', 'Api\PagamentopessoasController@index');
Route::post('pagamentopessoas', 'Api\PagamentopessoasController@store');
Route::get('pagamentopessoas/{pagamentopessoa}', 'Api\PagamentopessoasController@show');
Route::put('pagamentopessoas/{pagamentopessoa}', 'Api\PagamentopessoasController@update');
Route::delete('pagamentopessoas/{pagamentopessoa}', 'Api\PagamentopessoasController@destroy');

Route::get('patrimonios', 'Api\PatrimoniosController@index');
Route::post('patrimonios', 'Api\PatrimoniosController@store');
Route::get('patrimonios/{patrimonio}', 'Api\PatrimoniosController@show');
Route::put('patrimonios/{patrimonio}', 'Api\PatrimoniosController@update');
Route::delete('patrimonios/{patrimonio}', 'Api\PatrimoniosController@destroy');

Route::get('pedidocompras', 'Api\PedidocomprasController@index');
Route::post('pedidocompras', 'Api\PedidocomprasController@store');
Route::get('pedidocompras/{pedidocompra}', 'Api\PedidocomprasController@show');
Route::put('pedidocompras/{pedidocompra}', 'Api\PedidocomprasController@update');
Route::delete('pedidocompras/{pedidocompra}', 'Api\PedidocomprasController@destroy');

Route::get('pessoas', 'Api\PessoasController@index');
Route::post('pessoas', 'Api\PessoasController@store');
Route::get('pessoas/{pessoa}', 'Api\PessoasController@show');
Route::put('pessoas/{pessoa}', 'Api\PessoasController@update');
Route::delete('pessoas/{pessoa}', 'Api\PessoasController@destroy');

Route::get('pessoaTelefones', 'Api\PessoaTelefoneController@index');
Route::post('pessoaTelefones', 'Api\PessoaTelefoneController@store');
Route::get('pessoaTelefones/{pessoaTelefone}', 'Api\PessoaTelefoneController@show');
Route::put('pessoaTelefones/{pessoaTelefone}', 'Api\PessoaTelefoneController@update');
Route::delete('pessoaTelefones/{pessoaTelefone}', 'Api\PessoaTelefoneController@destroy');

Route::get('pessoaEmails', 'Api\PessoaEmailController@index');
Route::post('pessoaEmails', 'Api\PessoaEmailController@store');
Route::get('pessoaEmails/{pessoaEmail}', 'Api\PessoaEmailController@show');
Route::put('pessoaEmails/{pessoaEmail}', 'Api\PessoaEmailController@update');
Route::delete('pessoaEmails/{pessoaEmail}', 'Api\PessoaEmailController@destroy');

Route::get('pessoaEnderecos', 'Api\PessoaEnderecoController@index');
Route::post('pessoaEnderecos', 'Api\PessoaEnderecoController@store');
Route::get('pessoaEnderecos/{pessoaEndereco}', 'Api\PessoaEnderecoController@show');
Route::put('pessoaEnderecos/{pessoaEndereco}', 'Api\PessoaEnderecoController@update');
Route::delete('pessoaEnderecos/{pessoaEndereco}', 'Api\PessoaEnderecoController@destroy');

Route::get('pils', 'Api\PilsController@index');
Route::post('pils', 'Api\PilsController@store');
Route::get('pils/{pil}', 'Api\PilsController@show');
Route::put('pils/{pil}', 'Api\PilsController@update');
Route::delete('pils/{pil}', 'Api\PilsController@destroy');

Route::get('pontos', 'Api\PontosController@index');
Route::post('pontos', 'Api\PontosController@store');
Route::get('pontos/{ponto}', 'Api\PontosController@show');
Route::put('pontos/{ponto}', 'Api\PontosController@update');
Route::delete('pontos/{ponto}', 'Api\PontosController@destroy');
Route::post('pontos/checkin/{escala}', 'Api\PontosController@checkin'); // Custon
Route::post('pontos/checkout/{escala}', 'Api\PontosController@checkout'); // Custon
Route::get('pontos/escala/{escala}', 'Api\PontosController@buscaPontosPorIdEscala');

Route::get('prescricoesbs', 'Api\PrescricoesbsController@index');
Route::post('prescricoesbs', 'Api\PrescricoesbsController@store');
Route::get('prescricoesbs/{prescricaob}', 'Api\PrescricoesbsController@show');
Route::put('prescricoesbs/{prescricaob}', 'Api\PrescricoesbsController@update');
Route::delete('prescricoesbs/{prescricaob}', 'Api\PrescricoesbsController@destroy');

Route::get('prestadores', 'Api\PrestadoresController@index');
Route::post('prestadores', 'Api\PrestadoresController@store');
Route::get('prestadores/{prestador}', 'Api\PrestadoresController@show');
Route::put('prestadores/{prestador}', 'Api\PrestadoresController@update');
Route::delete('prestadores/{prestador}', 'Api\PrestadoresController@destroy');
Route::get('prestadores/{prestador}/meuspacientes', 'Api\PrestadoresController@meuspacientes'); // Custon

Route::get('prestadorFormacao', 'Api\PrestadorFormacaoController@index');
Route::post('prestadorFormacao', 'Api\PrestadorFormacaoController@store');
Route::get('prestadorFormacao/{prestadorFormacao}', 'Api\PrestadorFormacaoController@show');
Route::put('prestadorFormacao/{prestadorFormacao}', 'Api\PrestadorFormacaoController@update');
Route::delete('prestadorFormacao/{prestadorFormacao}', 'Api\PrestadorFormacaoController@destroy');
Route::get(
    'prestadorFormacao/{prestadorFormacao}/downloadFile',
    'Api\PrestadorFormacaoController@downloadFile'
); // Custon

Route::get('produtos', 'Api\ProdutosController@index');
Route::post('produtos', 'Api\ProdutosController@store');
Route::get('produtos/{produto}', 'Api\ProdutosController@show');
Route::put('produtos/{produto}', 'Api\ProdutosController@update');
Route::delete('produtos/{produto}', 'Api\ProdutosController@destroy');

Route::get('profissionais', 'Api\ProfissionaisController@index');
Route::post('profissionais', 'Api\ProfissionaisController@store');
Route::get('profissionais/{profissional}', 'Api\ProfissionaisController@show');
Route::put('profissionais/{profissional}', 'Api\ProfissionaisController@update');
Route::delete('profissionais/{profissional}', 'Api\ProfissionaisController@destroy');

Route::get('relatorios', 'Api\RelatoriosController@index');
Route::post('relatorios', 'Api\RelatoriosController@store');
Route::get('relatorios/{relatorio}', 'Api\RelatoriosController@show');
Route::put('relatorios/{relatorio}', 'Api\RelatoriosController@update');
Route::delete('relatorios/{relatorio}', 'Api\RelatoriosController@destroy');
Route::get('relatoriosOfOrdemservico/{ordemservico}', 'Api\RelatoriosController@relatoriosOfOrdemservico');
Route::get('relatorios/escala/{escala}', 'Api\RelatoriosController@buscaRelatoriosDaEscala');

Route::get('relatorioescalas', 'Api\RelatorioescalasController@index');
Route::post('relatorioescalas/{escala}', 'Api\RelatorioescalasController@store');
Route::get('relatorioescalas/{relatorioescala}', 'Api\RelatorioescalasController@show');
Route::put('relatorioescalas/{relatorioescala}', 'Api\RelatorioescalasController@update');
Route::delete('relatorioescalas/{relatorioescala}', 'Api\RelatorioescalasController@destroy');

Route::get('requisicoes', 'Api\RequisicoesController@index');
Route::post('requisicoes', 'Api\RequisicoesController@store');
Route::get('requisicoes/{requisicao}', 'Api\RequisicoesController@show');
Route::put('requisicoes/{requisicao}', 'Api\RequisicoesController@update');
Route::delete('requisicoes/{requisicao}', 'Api\RequisicoesController@destroy');

Route::get('requisicaoprodudos', 'Api\RequisicaoProdutosController@index');
Route::post('requisicaoprodudos', 'Api\RequisicaoProdutosController@store');
Route::get('requisicaoprodudos/{requisicaoProduto}', 'Api\RequisicaoProdutosController@show');
Route::put('requisicaoprodudos/{requisicaoProduto}', 'Api\RequisicaoProdutosController@update');
Route::delete('requisicaoprodudos/{requisicaoProduto}', 'Api\RequisicaoProdutosController@destroy');

Route::get('responsaveis', 'Api\ResponsaveisController@index');
Route::post('responsaveis', 'Api\ResponsaveisController@store');
Route::get('responsaveis/{responsavel}', 'Api\ResponsaveisController@show');
Route::put('responsaveis/{responsavel}', 'Api\ResponsaveisController@update');
Route::delete('responsaveis/{responsavel}', 'Api\ResponsaveisController@destroy');

Route::get('saidas', 'Api\SaidasController@index');
Route::post('saidas', 'Api\SaidasController@store');
Route::get('saidas/{saida}', 'Api\SaidasController@show');
Route::put('saidas/{saida}', 'Api\SaidasController@update');
Route::delete('saidas/{saida}', 'Api\SaidasController@destroy');

Route::get('salas', 'Api\SalasController@index');
Route::post('salas', 'Api\SalasController@store');
Route::get('salas/{sala}', 'Api\SalasController@show');
Route::put('salas/{sala}', 'Api\SalasController@update');
Route::delete('salas/{sala}', 'Api\SalasController@destroy');

Route::get('servicos', 'Api\ServicosController@index');
Route::post('servicos', 'Api\ServicosController@store');
Route::get('servicos/{servico}', 'Api\ServicosController@show');
Route::put('servicos/{servico}', 'Api\ServicosController@update');
Route::delete('servicos/{servico}', 'Api\ServicosController@destroy');
Route::get('servicos/empresa/{empresa}', 'Api\ServicosController@indexbyempresa');

Route::get('setores', 'Api\SetoresController@index');
Route::post('setores', 'Api\SetoresController@store');
Route::get('setores/{setor}', 'Api\SetoresController@show');
Route::put('setores/{setor}', 'Api\SetoresController@update');
Route::delete('setores/{setor}', 'Api\SetoresController@destroy');

Route::get('telefones', 'Api\TelefonesController@index');
Route::post('telefones', 'Api\TelefonesController@store');
Route::get('telefones/{telefone}', 'Api\TelefonesController@show');
Route::put('telefones/{telefone}', 'Api\TelefonesController@update');
Route::delete('telefones/{telefone}', 'Api\TelefonesController@destroy');

Route::get('tipopessoas', 'Api\TipopessoasController@index');
Route::post('tipopessoas', 'Api\TipopessoasController@store');
Route::get('tipopessoas/{tipopessoa}', 'Api\TipopessoasController@show');
Route::put('tipopessoas/{tipopessoa}', 'Api\TipopessoasController@update');
Route::delete('tipopessoas/{tipopessoa}', 'Api\TipopessoasController@destroy');

Route::get('tipoprodutos', 'Api\TipoprodutosController@index');
Route::post('tipoprodutos', 'Api\TipoprodutosController@store');
Route::get('tipoprodutos/{tipoproduto}', 'Api\TipoprodutosController@show');
Route::put('tipoprodutos/{tipoproduto}', 'Api\TipoprodutosController@update');
Route::delete('tipoprodutos/{tipoproduto}', 'Api\TipoprodutosController@destroy');

Route::get('transcricoes', 'Api\TranscricoesController@index');
Route::post('transcricoes', 'Api\TranscricoesController@store');
Route::get('transcricoes/{transcricao}', 'Api\TranscricoesController@show');
Route::put('transcricoes/{transcricao}', 'Api\TranscricoesController@update');
Route::delete('transcricoes/{transcricao}', 'Api\TranscricoesController@destroy');
Route::get('transcricoes/count/{empresa}', 'Api\TranscricoesController@quantidadetranscricoes');

Route::delete('transcricaoprodutos/{transcricao_produto}', 'Api\TranscricaoProdutoController@destroy');

Route::get('unidademedidas', 'Api\UnidademedidasController@index');
Route::post('unidademedidas', 'Api\UnidademedidasController@store');
Route::get('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@show');
Route::put('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@update');
Route::delete('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@destroy');

Route::get('users', 'Api\UsersController@index');
Route::post('users', 'Api\UsersController@store');
Route::get('users/{user}', 'Api\UsersController@show');
Route::put('users/{user}', 'Api\UsersController@update');
Route::put('users/{user}/updateEmail', 'Api\UsersController@updateEmail');
Route::delete('users/{user}', 'Api\UsersController@destroy');

Route::get('userAcessos', 'Api\UserAcessoController@index');
Route::post('userAcessos', 'Api\UserAcessoController@store');
Route::get('userAcessos/{userAcesso}', 'Api\UserAcessoController@show');
Route::put('userAcessos/{userAcesso}', 'Api\UserAcessoController@update');
Route::delete('userAcessos/{userAcesso}', 'Api\UserAcessoController@destroy');

Route::get('vendas', 'Api\VendasController@index');
Route::post('vendas', 'Api\VendasController@store');
Route::get('vendas/{venda}', 'Api\VendasController@show');
Route::put('vendas/{venda}', 'Api\VendasController@update');
Route::delete('vendas/{venda}', 'Api\VendasController@destroy');
Route::post('vendas/cadastrarCliente', 'Api\VendasController@cadastrarCliente');
















// Route::get($uri, $callback);
// Route::post($uri, $callback);
// Route::put($uri, $callback);
// Route::patch($uri, $callback);
// Route::delete($uri, $callback);
// Route::options($uri, $callback);

/*------------------------------------------- Novas Rotas -------------------------------------------*/

/*------------- Rotas Utilizando Token -------------*/
Route::group(['middleware' => 'auth:api'], function () {
    /*----------------- Web -----------------*/
    Route::prefix('web')->group(function () {
        Route::prefix('ordemservicoAcesso')->group(function () {
            Route::get('listaPorOrdemservico', [OrdemservicoAcessoController::class, 'listaDeAcessosPorOrdemservico']);
            Route::put('check/{ordemservicoAcesso}', [OrdemservicoAcessoController::class, 'checkOrdemservicoAcesso']);
        });
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
