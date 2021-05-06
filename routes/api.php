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
            // Route::get('clientes/list', 'Api\Web\GestaoOrcamentaria\ClientesController@index');
            // Route::get('clientes/{cliente}', 'Api\Web\GestaoOrcamentaria\ClientesController@show');
            // Route::post('clientes', 'Api\Web\GestaoOrcamentaria\ClientesController@store');
            // Route::put('clientes/{cliente}', 'Api\Web\GestaoOrcamentaria\ClientesController@update');
            // Route::delete('clientes/{cliente}', 'Api\Web\GestaoOrcamentaria\ClientesController@destroy');

            Route::get('dashboard/dashboarContratosPorPoriodo', 'Api\Web\GestaoOrcamentaria\DashboardController@dashboarContratosPorPoriodo');

            // Route::get('orcamentos/list/{empresa}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@index');
            // Route::get('orcamentos/{orcamento}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@show');
            // Route::post('orcamentos', 'Api\Web\GestaoOrcamentaria\OrcamentosController@store');
            // Route::put('orcamentos/{orcamento}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@update');
            // Route::delete('orcamentos/{orcamento}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@destroy');
            // Route::post('orcamentos/enviarOrcamentoPorEmail', 'Api\Web\GestaoOrcamentaria\OrcamentosController@enviarOrcamentoPorEmail');

            // Route::get('contratos/getAllOrdensServicos', 'Api\Web\GestaoOrcamentaria\OrdemservicosController@getAllOrdensServicos');
            // Route::get('contratos/dashboardGroupByMotivoDesativados', 'Api\Web\GestaoOrcamentaria\OrdemservicosController@dashboardGroupByMotivoDesativados');
            // Route::get('contratos/dashboardGroupByStatusAtivadosDesativados', 'Api\Web\GestaoOrcamentaria\OrdemservicosController@dashboardGroupByStatusAtivadosDesativados');

            // Route::get('pacientes/list', 'Api\Web\PacientesController@index');
            // Route::get('pacientes/{paciente}', 'Api\Web\PacientesController@show');
            // Route::post('pacientes', 'Api\Web\PacientesController@store');
            // Route::put('pacientes/{paciente}', 'Api\Web\PacientesController@update');
            // Route::delete('pacientes/{paciente}', 'Api\Web\PacientesController@destroy');

            // Route::get('responsaveis/list', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@index');
            // Route::get('responsaveis/{responsavel}', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@show');
            // Route::post('responsaveis', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@store');
            // Route::put('responsaveis/{responsavel}', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@update');
            // Route::delete('responsaveis/{responsavel}', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@destroy');


            // Route::get('servicos/list', 'Api\Web\GestaoOrcamentaria\ServicosController@index');
            // Route::get('servicos/listServicosFormacoes', 'Api\Web\GestaoOrcamentaria\ServicosController@listServicosFormacoes');
            // Route::post('servicos', 'Api\Web\GestaoOrcamentaria\ServicosController@store');
            // Route::get('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@show');
            // Route::put('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@update');
            // Route::delete('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@destroy');
        });

        // Route::prefix('departamentoPessoal')->group(function () {
        //     // Route::get('pontos/pontosPrestadores', 'Api\Web\DepartamentoPessoal\PontosController@pontosPrestadores');
        //     // Route::post('escalas/updateServicoOfEscala/{escala}', 'Api\Web\DepartamentoPessoal\EscalasController@updateServicoOfEscala');
        //     // Route::get('buscarPagamentosPessoaPorPeriodoEmpresaId', 'Api\Web\DepartamentoPessoal\PagamentopessoasController@buscarPagamentosPessoaPorPeriodoEmpresaId');
        //     // Route::get('buscalistadeconselhospodidpessoa/{pessoa}', 'Api\Web\PrestadoresController@buscalistadeconselhospodidpessoa');
        //     // Route::get('buscalistadebancospodidpessoa/{pessoa}', 'Api\Web\PrestadoresController@buscalistadebancospodidpessoa');
        //     // Route::get('buscalistadetelefonespodidpessoa/{pessoa}', 'Api\Web\PrestadoresController@buscalistadetelefonespodidpessoa');
        //     // Route::get('buscalistadeenderecospodidpessoa/{pessoa}', 'Api\Web\PrestadoresController@buscalistadeenderecospodidpessoa');
        //     // Route::post('salvarconselho', 'Api\Web\PrestadoresController@salvarconselho');
        //     // Route::post('salvartelefone', 'Api\Web\PrestadoresController@salvartelefone');
        //     // Route::post('salvarbanco', 'Api\Web\PrestadoresController@salvarbanco');
        //     // Route::post('salvarendereco', 'Api\Web\PrestadoresController@salvarendereco');
        //     // Route::delete('deletarconselho/{conselho}', 'Api\Web\PrestadoresController@deletarconselho');
        //     // Route::delete('deletarbanco/{dadosbancario}', 'Api\Web\PrestadoresController@deletarbanco');
        //     // Route::delete('deletartelefone/{pessoaTelefone}', 'Api\Web\PrestadoresController@deletartelefone');
        //     // Route::delete('deletarendereco/{pessoaEndereco}', 'Api\Web\PrestadoresController@deletarendereco');
        // });

        // Route::prefix('financeiro')->group(function () {
        //     Route::get('categorianaturezas', 'Api\Web\Financeiro\CategorianaturezasController@index');
        //     Route::post('categorianaturezas', 'Api\Web\Financeiro\CategorianaturezasController@store');
        //     Route::get('categorianaturezas/{categorianatureza}', 'Api\Web\Financeiro\CategorianaturezasController@show');
        //     Route::put('categorianaturezas/{categorianatureza}', 'Api\Web\Financeiro\CategorianaturezasController@update');
        //     Route::delete('categorianaturezas/{categorianatureza}', 'Api\Web\Financeiro\CategorianaturezasController@destroy');

            // Route::get('naturezas', 'Api\Web\Financeiro\NaturezasController@index');
            // Route::post('naturezas', 'Api\Web\Financeiro\NaturezasController@store');
            // Route::get('naturezas/{natureza}', 'Api\Web\Financeiro\NaturezasController@show');
            // Route::put('naturezas/{natureza}', 'Api\Web\Financeiro\NaturezasController@update');
            // Route::delete('naturezas/{natureza}', 'Api\Web\Financeiro\NaturezasController@destroy');
        // });

        Route::prefix('recursosHumanos')->group(function () {
            // Route::post('profissionais/novoProfissional', 'Api\Web\RecursosHumanos\ProfissionaisController@novoProfissional');
            Route::get('dashboard/dashboardProfissionaisExternos', 'Api\Web\RecursosHumanos\DashboardController@dashboardProfissionaisExternos');
            Route::get('dashboard/dashboardMapaPacientesPorEspecialidade', 'Api\Web\RecursosHumanos\DashboardController@dashboardMapaPacientesPorEspecialidade');

            // Route::get('beneficios', 'Api\Web\RecursosHumanos\BeneficiosController@index');
            // Route::post('beneficios', 'Api\Web\RecursosHumanos\BeneficiosController@store');
            // Route::get('beneficios/{beneficio}', 'Api\Web\RecursosHumanos\BeneficiosController@show');
            // Route::put('beneficios/{beneficio}', 'Api\Web\RecursosHumanos\BeneficiosController@update');
            // Route::delete('beneficios/{beneficio}', 'Api\Web\RecursosHumanos\BeneficiosController@destroy');

            // Route::get('cargos', 'Api\Web\RecursosHumanos\CargosController@index');
            // Route::post('cargos', 'Api\Web\RecursosHumanos\CargosController@store');
            // Route::get('cargos/{cargo}', 'Api\Web\RecursosHumanos\CargosController@show');
            // Route::put('cargos/{cargo}', 'Api\Web\RecursosHumanos\CargosController@update');
            // Route::delete('cargos/{cargo}', 'Api\Web\RecursosHumanos\CargosController@destroy');

            // Route::get('convenios', 'Api\Web\RecursosHumanos\ConveniosController@index');
            // Route::post('convenios', 'Api\Web\RecursosHumanos\ConveniosController@store');
            // Route::get('convenios/{convenio}', 'Api\Web\RecursosHumanos\ConveniosController@show');
            // Route::put('convenios/{convenio}', 'Api\Web\RecursosHumanos\ConveniosController@update');
            // Route::delete('convenios/{convenio}', 'Api\Web\RecursosHumanos\ConveniosController@destroy');

            // Route::get('profissionais', 'Api\Web\RecursosHumanos\ProfissionaisController@index');
            // Route::get('profissionais/{profissional}', 'Api\Web\RecursosHumanos\ProfissionaisController@show');
            // Route::put('profissionais/{profissional}', 'Api\Web\RecursosHumanos\ProfissionaisController@update');
            // Route::delete('profissionais/{profissional}', 'Api\Web\RecursosHumanos\ProfissionaisController@destroy');

            // Route::get('setores', 'Api\Web\RecursosHumanos\SetoresController@index');
            // Route::post('setores', 'Api\Web\RecursosHumanos\SetoresController@store');
            // Route::get('setores/{setor}', 'Api\Web\RecursosHumanos\SetoresController@show');
            // Route::put('setores/{setor}', 'Api\Web\RecursosHumanos\SetoresController@update');
            // Route::delete('setores/{setor}', 'Api\Web\RecursosHumanos\SetoresController@destroy');
        });
        // Route::prefix('estoque')->group(function () {
        //     Route::get('movimentacaoEstoque', 'Api\Web\Estoque\ProdutosController@movimentacaoEstoque');
        // });

        // novoProfissional

        Route::prefix('responsavel')->group(function () {
            // Route::get('escalas/listEscalasByIdResponsavel', 'Api\Web\Responsavel\EscalasController@listEscalasByIdResponsavel');
            // Route::get('escalas/listEscalasByIdOrdemServico/{ordemservico}', 'Api\Web\Responsavel\EscalasController@listEscalasByIdOrdemServico');
            // Route::post('escalas/assinar', 'Api\Web\Responsavel\EscalasController@assinar');
            // Route::get('escalas/dashboard', 'Api\Web\Responsavel\EscalasController@dashboard');
            // Route::get('escalas/dashboardPegarTodosOsRegistrosPorIdDaEmpresa', 'Api\Web\Responsavel\EscalasController@dashboardPegarTodosOsRegistrosPorIdDaEmpresa');
            Route::get('dashboard/relatorioDiario', 'Api\Web\Responsavel\DashboardController@relatorioDiario');
            Route::get('dashboard/relatorioProdutividade', 'Api\Web\Responsavel\DashboardController@relatorioProdutividade');
        });
        Route::get('buscaProfissionaisInternosPagamento', 'Api\Web\RecursosHumanos\ProfissionaisController@buscaProfissionaisInternosPagamento');
        Route::get('meu-perfil', 'Api\Web\RecursosHumanos\ProfissionaisController@meuperfil');
        Route::put('meu-perfil/atualizarFotoPerfil', 'Api\Web\RecursosHumanos\ProfissionaisController@atualizarFotoPerfil');

        // Route::get('documentos/listDocumentosByEmpresa', 'Api\Web\DocumentosController@listDocumentosByEmpresa');
        // Route::get('documentos/listDocumentosByConvenio', 'Api\Web\DocumentosController@listDocumentosByConvenio');
        // Route::get('documentos/listDocumentosByResponsavel', 'Api\Web\DocumentosController@listDocumentosByResponsavel');
        // Route::get('documentos/listDocumentos', 'Api\Web\DocumentosController@listDocumentos');
        // Route::post('documentos/newDocumento', 'Api\Web\DocumentosController@newDocumento');
        // Route::get('documentos/download/{documento}', 'Api\Web\DocumentosController@download');
        // Route::delete('documentos/delete/{documento}', 'Api\Web\DocumentosController@delete');

        Route::get('escalas/dashboard', 'Api\Web\EscalasController@dashboard');

        // Route::get('prestadores/listNomePrestadores', 'Api\Web\PrestadoresController@listNomePrestadores');
        // Route::get('prestadores/listPrestadoresComFormacoes', 'Api\Web\PrestadoresController@listPrestadoresComFormacoes');

        // Route::get('pacientes/listNomePacientes', 'Api\Web\PacientesController@listNomePacientes');

        // Route::get('ordemservicos/listaOrdemServicosEscalas', 'Api\Web\GestaoOrcamentaria\OrdemservicosController@listaOrdemServicosEscalas');

        // Route::post('pontos/checkin/{escala}', 'Api\PontosController@checkin'); // Custon
        // Route::post('pontos/checkout/{escala}', 'Api\PontosController@checkout'); // Custon
    });


    // Route::get('ordemservicos/listaOrdemServicosEscalas', 'Api\OrdemservicosController@listaOrdemServicosEscalas');
    // Route::get('transcricoes/listaTranscricoes', 'Api\Web\AreaClinica\TranscricoesController@listaTranscricoes');
});

// Route::get('getEscalasHoje', 'Api\EscalasController@getEscalasHoje')->middleware('auth:api');
Route::get('prestadores/atribuicao', 'Api\Web\PrestadoresController@buscaprestadoresporfiltro'); // MUDAR AQUII DEPOIS

// Route::get('acaomedicamentos', 'Api\AcaomedicamentosController@index');
// Route::post('acaomedicamentos', 'Api\AcaomedicamentosController@store');
// Route::get('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@show');
// Route::put('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@update');
// Route::delete('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@destroy');

// Route::get('acessos', 'Api\AcessosController@index');
// Route::post('acessos', 'Api\AcessosController@store');
// Route::get('acessos/{acesso}', 'Api\AcessosController@show');
// Route::put('acessos/{acesso}', 'Api\AcessosController@update');
// Route::delete('acessos/{acesso}', 'Api\AcessosController@destroy');

// Route::get('bancos', 'Api\BancosController@index');
// Route::post('bancos', 'Api\BancosController@store');
// Route::get('bancos/{banco}', 'Api\BancosController@show');
// Route::put('bancos/{banco}', 'Api\BancosController@update');
// Route::delete('bancos/{banco}', 'Api\BancosController@destroy');





// Route::get('cidades', 'Api\CidadesController@index');
// Route::post('cidades', 'Api\CidadesController@store');
// Route::get('cidades/{cidade}', 'Api\CidadesController@show');
// Route::put('cidades/{cidade}', 'Api\CidadesController@update');
// Route::delete('cidades/{cidade}', 'Api\CidadesController@destroy');

// Route::get('certificadoprestadores', 'Api\CertificadoprestadoresController@index');
// Route::post('certificadoprestadores', 'Api\CertificadoprestadoresController@store');
// Route::get('certificadoprestadores/{certificadoprestador}', 'Api\CertificadoprestadoresController@show');
// Route::put('certificadoprestadores/{certificadoprestador}', 'Api\CertificadoprestadoresController@update');
// Route::delete('certificadoprestadores/{certificadoprestador}', 'Api\CertificadoprestadoresController@destroy');

// Route::get('cnabs', 'Api\CnabsController@index');
// Route::post('cnabs', 'Api\CnabsController@store');
// Route::get('cnabs/{cnab}/{tipo}', 'Api\CnabsController@show');
// Route::put('cnabs/{cnab}', 'Api\CnabsController@update');
// Route::delete('cnabs/{cnab}', 'Api\CnabsController@destroy');

// Route::get('comentariosmedicao', 'Api\Web\ComentariomedicaoController@index');
// Route::post('comentariosmedicao', 'Api\Web\ComentariomedicaoController@store');
// Route::get('comentariosmedicao/buscaComentariosPorIdMedicao/{medicao}', 'Api\Web\ComentariomedicaoController@buscaComentariosPorIdMedicao');
// Route::put('comentariosmedicao/{medicao}', 'Api\ComentariomedicaoController@update');
//Route::delete('comentariosmedicao/{medicao}', 'Api\ComentariomedicaoController@destroy');

// Route::get('conselhos', 'Api\ConselhosController@index');
// Route::post('conselhos', 'Api\ConselhosController@store');
// Route::get('conselhos/{conselho}', 'Api\ConselhosController@show');
// Route::put('conselhos/{conselho}', 'Api\ConselhosController@update');
// Route::delete('conselhos/{conselho}', 'Api\ConselhosController@destroy');

// Route::get('contas', 'Api\ContasController@index');
// Route::post('contas', 'Api\ContasController@store');
// Route::get('contas/{contas}', 'Api\ContasController@show');
// Route::put('contas/{contas}', 'Api\ContasController@update');
// Route::delete('contas/{contas}', 'Api\ContasController@destroy');

// Route::get('contasbancarias', 'Api\ContasbancariasController@index');
// Route::post('contasbancarias', 'Api\ContasbancariasController@store');
// Route::get('contasbancarias/{contasbancaria}', 'Api\ContasbancariasController@show');
// Route::put('contasbancarias/{contasbancaria}', 'Api\ContasbancariasController@update');
// Route::delete('contasbancarias/{contasbancaria}', 'Api\ContasbancariasController@destroy');




// Route::get('cotacaoproduto', 'Api\CotacaoProdutoController@index');
// Route::post('cotacaoproduto', 'Api\CotacaoProdutoController@store');
// Route::get('cotacaoproduto/{cotacaoproduto}', 'Api\CotacaoProdutoController@show');
// Route::put('cotacaoproduto/{cotacaoproduto}', 'Api\CotacaoProdutoController@update');
// Route::delete('cotacaoproduto/{cotacaoproduto}', 'Api\CotacaoProdutoController@destroy');


// Route::get('cuidadoEscalas', 'Api\CuidadoEscalasController@index');
// Route::post('cuidadoEscalas', 'Api\CuidadoEscalasController@store');
// Route::get('cuidadoEscalas/{cuidadoEscala}', 'Api\CuidadoEscalasController@show');
// Route::put('cuidadoEscalas/{cuidadoEscala}', 'Api\CuidadoEscalasController@update');
// Route::delete('cuidadoEscalas/{cuidadoEscala}', 'Api\CuidadoEscalasController@destroy');

// Route::get('cuidadoPacientes', 'Api\CuidadoPacienteController@index');
// Route::post('cuidadoPacientes', 'Api\CuidadoPacienteController@store');
// Route::get('cuidadoPacientes/{cuidadoPaciente}', 'Api\CuidadoPacienteController@show');
// Route::put('cuidadoPacientes/{cuidadoPaciente}', 'Api\CuidadoPacienteController@update');
// Route::delete('cuidadoPacientes/{cuidadoPaciente}', 'Api\CuidadoPacienteController@destroy');
// Route::get('cuidadoPacientes/paciente/{paciente}', 'Api\CuidadoPacienteController@buscacuidadosdopaciente');
// Route::get('cuidadoPacientes/groupby/{paciente}', 'Api\CuidadoPacienteController@groupbycuidadosdopaciente');

// Route::get('dadosbancarios', 'Api\DadosbancariosController@index');
// Route::post('dadosbancarios', 'Api\DadosbancariosController@store');
// Route::get('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@show');
// Route::put('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@update');
// Route::delete('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@destroy');

// Route::get('dadoscontratuais', 'Api\DadoscontratuaisController@index');
// Route::post('dadoscontratuais', 'Api\DadoscontratuaisController@store');
// Route::get('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@show');
// Route::put('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@update');
// Route::delete('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@destroy');

// Route::get('diagnosticossecundarios', 'Api\DiagnosticossecundariosController@index');
// Route::post('diagnosticossecundarios', 'Api\DiagnosticossecundariosController@store');
// Route::get('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@show');
// Route::put('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@update');
// Route::delete('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@destroy');


// Route::get('emails', 'Api\EmailsController@index');
// Route::post('emails', 'Api\EmailsController@store');
// Route::get('emails/{email}', 'Api\EmailsController@show');
// Route::put('emails/{email}', 'Api\EmailsController@update');
// Route::delete('emails/{email}', 'Api\EmailsController@destroy');

// Route::get('empresas', 'Api\EmpresasController@index');
// Route::post('empresas', 'Api\EmpresasController@store');
// Route::get('empresas/{empresa}', 'Api\EmpresasController@show');
// Route::put('empresas/{empresa}', 'Api\EmpresasController@update');
// Route::delete('empresas/{empresa}', 'Api\EmpresasController@destroy');

// Route::get('empresaPrestador', 'Api\EmpresaPrestadorController@index');
// Route::post('empresaPrestador', 'Api\EmpresaPrestadorController@store');
// Route::get('empresaPrestador/{empresaPrestador}', 'Api\EmpresaPrestadorController@show');
// Route::put('empresaPrestador/{empresaPrestador}', 'Api\EmpresaPrestadorController@update');
// Route::delete('empresaPrestador/{empresaPrestador}', 'Api\EmpresaPrestadorController@destroy');
// Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\EmpresaPrestadorController@downloadFile');
// Route::get('empresaPrestador/empresa/{empresa}', 'Api\EmpresaPrestadorController@indexbyempresa');
// Route::get('empresaPrestador/count/{empresa}', 'Api\EmpresaPrestadorController@quantidadeempresaprestador');

// Route::get('enderecos', 'Api\EnderecosController@index');
// Route::post('enderecos', 'Api\EnderecosController@store');
// Route::get('enderecos/{endereco}', 'Api\EnderecosController@show');
// Route::put('enderecos/{endereco}', 'Api\EnderecosController@update');
// Route::delete('enderecos/{endereco}', 'Api\EnderecosController@destroy');

// Route::get('entradas', 'Api\EntradasController@index');
// Route::post('entradas', 'Api\EntradasController@store');
// Route::post('entradas/cadastrarFornecedor', 'Api\EntradasController@cadastrarFornecedor');
// Route::get('entradas/{entrada}', 'Api\EntradasController@show');
// Route::put('entradas/{entrada}', 'Api\EntradasController@update');
// Route::delete('entradas/{entrada}', 'Api\EntradasController@destroy');

// Route::get('escalas', 'Api\EscalasController@index');
// Route::get('escalas/{escala}', 'Api\EscalasController@show');

//Route::group(['middleware' => 'auth:api'], function () {
// Route::post('escalas', 'Api\EscalasController@store');
// Route::put('escalas/{escala}', 'Api\EscalasController@update');
// Route::delete('escalas/{escala}', 'Api\EscalasController@destroy');
//});

// Route::get('escalas/empresa/{empresa}/dia', 'Api\EscalasController@buscaescalasdodia');
// Route::get('escalas/paciente/{paciente}/data1/{data1}/data2/{data2}', 'Api\EscalasController@buscaPontosPorPeriodoEPaciente');

// Route::get('formacoes', 'Api\FormacoesController@index');
// Route::post('formacoes', 'Api\FormacoesController@store');
// Route::get('formacoes/{formacao}', 'Api\FormacoesController@show');
// Route::put('formacoes/{formacao}', 'Api\FormacoesController@update');
// Route::delete('formacoes/{formacao}', 'Api\FormacoesController@destroy');




// Route::get('historicoorcamentos', 'Api\HistoricoorcamentosController@index');
// Route::post('historicoorcamentos', 'Api\HistoricoorcamentosController@store');
// Route::get('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@show');
// Route::put('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@update');
// Route::delete('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@destroy');

// Route::get('horariostrabalho', 'Api\HorariostrabalhoController@index');
// Route::post('horariostrabalho', 'Api\HorariostrabalhoController@store');
// Route::get('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@show');
// Route::put('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@update');
// Route::delete('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@destroy');

// Route::get('homecares', 'Api\HomecaresController@index');
// Route::post('homecares', 'Api\HomecaresController@store');
// Route::get('homecares/{homecare}', 'Api\HomecaresController@show');
// Route::put('homecares/{homecare}', 'Api\HomecaresController@update');
// Route::delete('homecares/{homecare}', 'Api\HomecaresController@destroy');

// Route::get('impostos', 'Api\ImpostosController@index');
// Route::post('impostos', 'Api\ImpostosController@store');
// Route::get('impostos/{imposto}', 'Api\ImpostosController@show');
// Route::put('impostos/{imposto}', 'Api\ImpostosController@update');
// Route::delete('impostos/{imposto}', 'Api\ImpostosController@destroy');

// Route::get('marcas', 'Api\MarcasController@index');
// Route::post('marcas', 'Api\MarcasController@store');
// Route::get('marcas/{marca}', 'Api\MarcasController@show');
// Route::put('marcas/{marca}', 'Api\MarcasController@update');
// Route::delete('marcas/{marca}', 'Api\MarcasController@destroy');

// Route::get('medicoes', 'Api\MedicoesController@index');
// Route::post('medicoes', 'Api\MedicoesController@store');
// Route::get('medicoes/{medicao}', 'Api\MedicoesController@show');
// Route::put('medicoes/{medicao}', 'Api\MedicoesController@update');
// Route::delete('medicoes/{medicao}', 'Api\MedicoesController@destroy');

// Route::get('monitoramentoescalas', 'Api\MonitoramentoescalasController@index');
// Route::post('monitoramentoescalas', 'Api\MonitoramentoescalasController@store');
// Route::get('monitoramentoescalas/{monitoramentoescala}', 'Api\MonitoramentoescalasController@show');
// Route::put('monitoramentoescalas/{monitoramentoescala}', 'Api\MonitoramentoescalasController@update');
// Route::delete('monitoramentoescalas/{monitoramentoescala}', 'Api\MonitoramentoescalasController@destroy');


// Route::get('orcamentos', 'Api\OrcamentosController@index');
// Route::post('orcamentos', 'Api\OrcamentosController@store');
// Route::get('orcamentos/{orcamento}', 'Api\OrcamentosController@show');
// Route::put('orcamentos/{orcamento}', 'Api\OrcamentosController@update');
// Route::put('alterarsituacao/{orcamento}', 'Api\OrcamentosController@alterarSituacao');
// Route::delete('orcamentos/{orcamento}', 'Api\OrcamentosController@destroy');

// Route::get('orcamentocustos', 'Api\OrcamentoCustosController@index');
// Route::post('orcamentocustos', 'Api\OrcamentoCustosController@store');
// Route::get('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@show');
// Route::put('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@update');
// Route::delete('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@destroy');

// Route::get('orcamentoprodutos', 'Api\OrcamentoProdutosController@index');
// Route::post('orcamentoprodutos', 'Api\OrcamentoProdutosController@store');
// Route::get('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@show');
// Route::put('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@update');
// Route::delete('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@destroy');

// Route::get('orcamentoservicos', 'Api\OrcamentoServicosController@index');
// Route::post('orcamentoservicos', 'Api\OrcamentoServicosController@store');
// Route::get('orcamentoservicos/{orcamentoServico}', 'Api\OrcamentoServicosController@show');
// Route::put('orcamentoservicos/{orcamentoServico}', 'Api\OrcamentoServicosController@update');
// Route::delete('orcamentoservicos/{orcamentoServico}', 'Api\OrcamentoServicosController@destroy');

// Route::get('ordemservicos', 'Api\OrdemservicosController@index');
// Route::post('ordemservicos', 'Api\OrdemservicosController@store');
// Route::get('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@show');
// Route::put('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@update');
// Route::delete('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@destroy');
// Route::get(
//     'ordemservicos/{ordemservico}/horariomedicamentos',
//     'Api\OrdemservicosController@horariomedicamentos'
// ); // Custon
// Route::get('ordemservicos/count/{empresa}', 'Api\OrdemservicosController@quantidadeordemservicos');
// Route::get('ordemservicos/groupbyservico/{empresa}', 'Api\OrdemservicosController@groupbyservicos');

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


Route::get('servicos', 'Api\ServicosController@index');
Route::post('servicos', 'Api\ServicosController@store');
Route::get('servicos/{servico}', 'Api\ServicosController@show');
Route::put('servicos/{servico}', 'Api\ServicosController@update');
Route::delete('servicos/{servico}', 'Api\ServicosController@destroy');
Route::get('servicos/empresa/{empresa}', 'Api\ServicosController@indexbyempresa');


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
