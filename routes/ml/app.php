<?php

use App\Http\Controllers\Api\App\v3_0_25\VersaoController;
use App\Http\Controllers\Web\Cidade\CidadesController;
use Illuminate\Support\Facades\Route;

Route::post('enviar_arquivos', 'Api\App\v3_0_21\ChamadosController@enviar_arquivos');
Route::post('enviar_arquivos', 'Api\App\v3_0_22\ChamadosController@enviar_arquivos');
Route::post('enviar_arquivos', 'Api\App\v3_0_24\ChamadosController@enviar_arquivos');
Route::post('enviar_arquivos', 'Api\App\v3_0_25\ChamadosController@enviar_arquivos');
Route::post('enviar_arquivos', 'Api\App\v3_1_0\ChamadosController@enviar_arquivos');

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix("app/v3_0_21")->group(function () {
        Route::post('acaomedicamentos', 'Api\App\v3_0_21\AcaomedicamentosController@store');

        Route::get('bancos', 'Api\App\v3_0_21\BancosController@index');

        Route::get('cidades/{uf}', 'Api\App\v3_0_21\CidadesController@index');

        Route::post('conselhos', 'Api\App\v3_0_21\ConselhosController@store');
        Route::put('conselhos/{conselho}', 'Api\App\v3_0_21\ConselhosController@update');
        Route::delete('conselhos/{conselho}', 'Api\App\v3_0_21\ConselhosController@destroy');

        Route::post('dadosbancarios', 'Api\App\v3_0_21\DadosbancariosController@store');
        Route::put('dadosbancarios/{dadosbancario}', 'Api\App\v3_0_21\DadosbancariosController@update');
        Route::delete('dadosbancarios/{dadosbancario}', 'Api\App\v3_0_21\DadosbancariosController@destroy');

        Route::get('empresaPrestador/meusContratos', 'Api\App\v3_0_21\EmpresaPrestadorController@index');
        Route::put('empresaPrestador/meusContratos/{empresaPrestador}', 'Api\App\v3_0_21\EmpresaPrestadorController@update');
        Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\App\v3_0_21\EmpresaPrestadorController@downloadFile');


        Route::get('escalas/listEscalasHoje', 'Api\App\v3_0_21\EscalasController@listEscalasHoje');
        Route::get('escalas/listEscalasMes', 'Api\App\v3_0_21\EscalasController@listEscalasMes');
        Route::get('escalas/getEscalaId/{escala}', 'Api\App\v3_0_21\EscalasController@getEscalaId');
        Route::get('escalas/getEscalaId/{escala}/cuidados', 'Api\App\v3_0_21\EscalasController@getCuidadosByEscalaId');

        Route::put('cuidadoEscalas/{cuidadoEscala}', 'Api\App\v3_0_21\CuidadoEscalasController@updateCuidado');

        Route::get('formacoes/listFormacoes', 'Api\App\v3_0_21\FormacoesController@listFormacoes');

        Route::get('monitoramentoescalas/{escala}', 'Api\App\v3_0_21\MonitoramentoescalasController@listaMonitoramento');
        Route::post('monitoramentoescalas', 'Api\App\v3_0_21\MonitoramentoescalasController@salvarMonitoramento');

        Route::post('prestadorFormacao/newPrestadorFormacao', 'Api\App\v3_0_21\PrestadorFormacaoController@newPrestadorFormacao');
        Route::delete('prestadorFormacao/{prestadorFormacao}', 'Api\App\v3_0_21\PrestadorFormacaoController@destroy');

        Route::get('pessoas/getPessoaPerfil', 'Api\App\v3_0_21\PessoasController@getPessoaPerfil');
        Route::put('pessoas/atualizaDadosPessoais/{pessoa}', 'Api\App\v3_0_21\PessoasController@atualizaDadosPessoais');

        Route::post('pessoaTelefones', 'Api\App\v3_0_21\PessoaTelefoneController@store');
        // Route::delete('pessoaTelefones/{pessoaTelefone}', 'Api\PessoaTelefoneController@destroy');

        Route::post('pessoaEmails', 'Api\App\v3_0_21\PessoaEmailController@store');
        Route::delete('pessoaEmails/{pessoaEmail}', 'Api\App\v3_0_21\PessoaEmailController@destroy');

        Route::post('pessoaEnderecos', 'Api\App\v3_0_21\PessoaEnderecoController@store');
        Route::put('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_0_21\PessoaEnderecoController@update');
        Route::delete('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_0_21\PessoaEnderecoController@destroy');

        // Route::post('pontos', 'Api\PontosController@store');
        Route::post('pontos/checkin/{escala}', 'Api\App\v3_0_21\PontosController@checkin'); // Custon
        Route::post('pontos/checkout/{escala}', 'Api\App\v3_0_21\PontosController@checkout'); // Custon
        Route::post('pontos/assinaturacheckout/{escala}', 'Api\App\v3_0_21\PontosController@assinaturacheckout'); // Custon

        Route::get('prestadores/meuspacientes', 'Api\App\v3_0_21\PrestadoresController@meuspacientes'); // Custon

        Route::get(
            'relatorios/{escala}',
            'Api\App\v3_0_21\RelatoriosController@listRelatoriosByEscalaId'
        );
        Route::get('relatorios/{escala}/list', 'Api\App\v3_0_21\RelatoriosController@getAllRelatoriosByEscalaId');
        Route::post('relatorios', 'Api\App\v3_0_21\RelatoriosController@store');
        Route::delete('relatorios/{relatorio}', 'Api\App\v3_0_21\RelatoriosController@destroy');

        Route::get('transcricoes/{ordemservico}', 'Api\App\v3_0_21\TranscricoesController@listTranscricoesByEscalaId');
        Route::get('pacientes/listMedicamentosByPaciente/{ordemservico}', 'Api\App\v3_0_21\TranscricoesController@listMedicamentosByPaciente');
        Route::get('chamados', 'Api\App\v3_0_21\ChamadosController@chamados');
        Route::get('get_pendencias', 'Api\App\v3_0_21\ChamadosController@get_pendencias');

        Route::post('criarchamado', 'Api\App\v3_0_21\ChamadosController@criarchamado');
        Route::post('enviarArquivos', 'Api\App\v3_0_21\ChamadosController@enviarArquivos');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix("app/v3_0_22")->group(function () {
        Route::post('acaomedicamentos', 'Api\App\v3_0_22\AcaomedicamentosController@store');

        Route::get('bancos', 'Api\App\v3_0_22\BancosController@index');

        Route::get('cidades/{uf}', 'Api\App\v3_0_22\CidadesController@index');

        Route::post('conselhos', 'Api\App\v3_0_22\ConselhosController@store');
        Route::put('conselhos/{conselho}', 'Api\App\v3_0_22\ConselhosController@update');
        Route::delete('conselhos/{conselho}', 'Api\App\v3_0_22\ConselhosController@destroy');

        Route::post('dadosbancarios', 'Api\App\v3_0_22\DadosbancariosController@store');
        Route::put('dadosbancarios/{dadosbancario}', 'Api\App\v3_0_22\DadosbancariosController@update');
        Route::delete('dadosbancarios/{dadosbancario}', 'Api\App\v3_0_22\DadosbancariosController@destroy');

        Route::get('empresaPrestador/meusContratos', 'Api\App\v3_0_22\EmpresaPrestadorController@index');
        Route::put('empresaPrestador/meusContratos/{empresaPrestador}', 'Api\App\v3_0_22\EmpresaPrestadorController@update');
        Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\App\v3_0_22\EmpresaPrestadorController@downloadFile');


        Route::get('escalas/listEscalasHoje', 'Api\App\v3_0_22\EscalasController@listEscalasHoje');
        Route::get('escalas/listEscalasMes', 'Api\App\v3_0_22\EscalasController@listEscalasMes');
        Route::get('escalas/getEscalaId/{escala}', 'Api\App\v3_0_22\EscalasController@getEscalaId');
        Route::get('escalas/getEscalaId/{escala}/cuidados', 'Api\App\v3_0_22\EscalasController@getCuidadosByEscalaId');

        Route::put('cuidadoEscalas/{cuidadoEscala}', 'Api\App\v3_0_22\CuidadoEscalasController@updateCuidado');

        Route::get('formacoes/listFormacoes', 'Api\App\v3_0_22\FormacoesController@listFormacoes');

        Route::get('monitoramentoescalas/{escala}', 'Api\App\v3_0_22\MonitoramentoescalasController@listaMonitoramento');
        Route::post('monitoramentoescalas', 'Api\App\v3_0_22\MonitoramentoescalasController@salvarMonitoramento');

        Route::post('prestadorFormacao/newPrestadorFormacao', 'Api\App\v3_0_22\PrestadorFormacaoController@newPrestadorFormacao');
        Route::delete('prestadorFormacao/{prestadorFormacao}', 'Api\App\v3_0_22\PrestadorFormacaoController@destroy');

        Route::get('pessoas/getPessoaPerfil', 'Api\App\v3_0_22\PessoasController@getPessoaPerfil');
        Route::put('pessoas/atualizaDadosPessoais/{pessoa}', 'Api\App\v3_0_22\PessoasController@atualizaDadosPessoais');

        Route::post('pessoaTelefones', 'Api\App\v3_0_22\PessoaTelefoneController@store');
        // Route::delete('pessoaTelefones/{pessoaTelefone}', 'Api\PessoaTelefoneController@destroy');

        Route::post('pessoaEmails', 'Api\App\v3_0_22\PessoaEmailController@store');
        Route::delete('pessoaEmails/{pessoaEmail}', 'Api\App\v3_0_22\PessoaEmailController@destroy');

        Route::post('pessoaEnderecos', 'Api\App\v3_0_22\PessoaEnderecoController@store');
        Route::put('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_0_22\PessoaEnderecoController@update');
        Route::delete('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_0_22\PessoaEnderecoController@destroy');

        // Route::post('pontos', 'Api\PontosController@store');
        Route::post('pontos/checkin/{escala}', 'Api\App\v3_0_22\PontosController@checkin'); // Custon
        Route::post('pontos/checkout/{escala}', 'Api\App\v3_0_22\PontosController@checkout'); // Custon
        Route::post('pontos/assinaturacheckout/{escala}', 'Api\App\v3_0_22\PontosController@assinaturacheckout'); // Custon

        Route::get('prestadores/meuspacientes', 'Api\App\v3_0_22\PrestadoresController@meuspacientes'); // Custon

        Route::get(
            'relatorios/{escala}',
            'Api\App\v3_0_22\RelatoriosController@listRelatoriosByEscalaId'
        );
        Route::get('relatorios/{escala}/list', 'Api\App\v3_0_22\RelatoriosController@getAllRelatoriosByEscalaId');
        Route::post('relatorios', 'Api\App\v3_0_22\RelatoriosController@store');
        Route::delete('relatorios/{relatorio}', 'Api\App\v3_0_22\RelatoriosController@destroy');

        Route::get('transcricoes/{ordemservico}', 'Api\App\v3_0_22\TranscricoesController@listTranscricoesByEscalaId');
        Route::get('pacientes/listMedicamentosByPaciente/{ordemservico}', 'Api\App\v3_0_22\TranscricoesController@listMedicamentosByPaciente');
        Route::get('chamados', 'Api\App\v3_0_22\ChamadosController@chamados');
        Route::get('get_pendencias', 'Api\App\v3_0_22\ChamadosController@get_pendencias');

        Route::post('criarchamado', 'Api\App\v3_0_22\ChamadosController@criarchamado');
        Route::post('enviarArquivos', 'Api\App\v3_0_22\ChamadosController@enviarArquivos');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix("app/v3_0_24")->group(function () {
        Route::post('acaomedicamentos', 'Api\App\v3_0_24\AcaomedicamentosController@store');

        Route::get('bancos', 'Api\App\v3_0_24\BancosController@index');

        Route::get('cidades/{uf}', 'Api\App\v3_0_24\CidadesController@index');

        Route::post('conselhos', 'Api\App\v3_0_24\ConselhosController@store');
        Route::put('conselhos/{conselho}', 'Api\App\v3_0_24\ConselhosController@update');
        Route::delete('conselhos/{conselho}', 'Api\App\v3_0_24\ConselhosController@destroy');

        Route::post('dadosbancarios', 'Api\App\v3_0_24\DadosbancariosController@store');
        Route::put('dadosbancarios/{dadosbancario}', 'Api\App\v3_0_24\DadosbancariosController@update');
        Route::delete('dadosbancarios/{dadosbancario}', 'Api\App\v3_0_24\DadosbancariosController@destroy');

        Route::get('empresaPrestador/meusContratos', 'Api\App\v3_0_24\EmpresaPrestadorController@index');
        Route::put('empresaPrestador/meusContratos/{empresaPrestador}', 'Api\App\v3_0_24\EmpresaPrestadorController@update');
        Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\App\v3_0_24\EmpresaPrestadorController@downloadFile');


        Route::get('escalas/listEscalasHoje', 'Api\App\v3_0_24\EscalasController@listEscalasHoje');
        Route::get('escalas/listEscalasMes', 'Api\App\v3_0_24\EscalasController@listEscalasMes');
        Route::get('escalas/getEscalaId/{escala}', 'Api\App\v3_0_24\EscalasController@getEscalaId');
        Route::get('escalas/getEscalaId/{escala}/cuidados', 'Api\App\v3_0_24\EscalasController@getCuidadosByEscalaId');

        Route::put('cuidadoEscalas/{cuidadoEscala}', 'Api\App\v3_0_24\CuidadoEscalasController@updateCuidado');

        Route::get('formacoes/listFormacoes', 'Api\App\v3_0_24\FormacoesController@listFormacoes');

        Route::get('monitoramentoescalas/{escala}', 'Api\App\v3_0_24\MonitoramentoescalasController@listaMonitoramento');
        Route::post('monitoramentoescalas', 'Api\App\v3_0_24\MonitoramentoescalasController@salvarMonitoramento');

        Route::post('prestadorFormacao/newPrestadorFormacao', 'Api\App\v3_0_24\PrestadorFormacaoController@newPrestadorFormacao');
        Route::delete('prestadorFormacao/{prestadorFormacao}', 'Api\App\v3_0_24\PrestadorFormacaoController@destroy');

        Route::get('pessoas/getPessoaPerfil', 'Api\App\v3_0_24\PessoasController@getPessoaPerfil');
        Route::put('pessoas/atualizaDadosPessoais/{pessoa}', 'Api\App\v3_0_24\PessoasController@atualizaDadosPessoais');

        Route::post('pessoaTelefones', 'Api\App\v3_0_24\PessoaTelefoneController@store');
        Route::delete('pessoaTelefones/{pessoaTelefone}', 'Api\PessoaTelefoneController@destroy');

        Route::post('pessoaEmails', 'Api\App\v3_0_24\PessoaEmailController@store');
        Route::delete('pessoaEmails/{pessoaEmail}', 'Api\App\v3_0_24\PessoaEmailController@destroy');

        Route::post('pessoaEnderecos', 'Api\App\v3_0_24\PessoaEnderecoController@store');
        Route::put('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_0_24\PessoaEnderecoController@update');
        Route::delete('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_0_24\PessoaEnderecoController@destroy');

        // Route::post('pontos', 'Api\PontosController@store');
        Route::post('pontos/checkin/{escala}', 'Api\App\v3_0_24\PontosController@checkin'); // Custon
        Route::post('pontos/checkout/{escala}', 'Api\App\v3_0_24\PontosController@checkout'); // Custon
        Route::post('pontos/assinaturacheckout/{escala}', 'Api\App\v3_0_24\PontosController@assinaturacheckout'); // Custon

        Route::get('prestadores/meuspacientes', 'Api\App\v3_0_24\PrestadoresController@meuspacientes'); // Custon

        Route::get(
            'relatorios/{escala}',
            'Api\App\v3_0_24\RelatoriosController@listRelatoriosByEscalaId'
        );
        Route::get('relatorios/{escala}/list', 'Api\App\v3_0_24\RelatoriosController@getAllRelatoriosByEscalaId');
        Route::post('relatorios', 'Api\App\v3_0_24\RelatoriosController@store');
        Route::delete('relatorios/{relatorio}', 'Api\App\v3_0_24\RelatoriosController@destroy');

        Route::get('transcricoes/{ordemservico}', 'Api\App\v3_0_24\TranscricoesController@listTranscricoesByEscalaId');
        Route::get('pacientes/listMedicamentosByPaciente/{ordemservico}', 'Api\App\v3_0_24\TranscricoesController@listMedicamentosByPaciente');
        Route::get('chamados', 'Api\App\v3_0_24\ChamadosController@chamados');
        Route::get('get_pendencias', 'Api\App\v3_0_24\ChamadosController@get_pendencias');

        Route::post('criarchamado', 'Api\App\v3_0_24\ChamadosController@criarchamado');
        Route::post('enviarArquivos', 'Api\App\v3_0_24\ChamadosController@enviarArquivos');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix("app/v3_0_25")->group(function () {
        Route::post('acaomedicamentos', 'Api\App\v3_0_25\AcaomedicamentosController@store');

        Route::get('bancos', 'Api\App\v3_0_25\BancosController@index');

        Route::get('cidades/{uf}', 'Api\App\v3_0_25\CidadesController@index');

        Route::post('conselhos', 'Api\App\v3_0_25\ConselhosController@store');
        Route::put('conselhos/{conselho}', 'Api\App\v3_0_25\ConselhosController@update');
        Route::delete('conselhos/{conselho}', 'Api\App\v3_0_25\ConselhosController@destroy');

        Route::post('dadosbancarios', 'Api\App\v3_0_25\DadosbancariosController@store');
        Route::put('dadosbancarios/{dadosbancario}', 'Api\App\v3_0_25\DadosbancariosController@update');
        Route::delete('dadosbancarios/{dadosbancario}', 'Api\App\v3_0_25\DadosbancariosController@destroy');

        Route::get('empresaPrestador/meusContratos', 'Api\App\v3_0_25\EmpresaPrestadorController@index');
        Route::put('empresaPrestador/meusContratos/{empresaPrestador}', 'Api\App\v3_0_25\EmpresaPrestadorController@update');
        Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\App\v3_0_25\EmpresaPrestadorController@downloadFile');

        Route::get('escalas/listEscalasHoje', 'Api\App\v3_0_25\EscalasController@listEscalasHoje');
        Route::get('escalas/listEscalasMes', 'Api\App\v3_0_25\EscalasController@listEscalasMes');
        Route::get('escalas/getEscalaId/{escala}', 'Api\App\v3_0_25\EscalasController@getEscalaId');
        Route::get('escalas/getEscalaId/{escala}/cuidados', 'Api\App\v3_0_25\EscalasController@getCuidadosByEscalaId');

        Route::put('cuidadoEscalas/{cuidadoEscala}', 'Api\App\v3_0_25\CuidadoEscalasController@updateCuidado');

        Route::get('formacoes/listFormacoes', 'Api\App\v3_0_25\FormacoesController@listFormacoes');

        Route::get('monitoramentoescalas/{escala}/list', 'Api\App\v3_0_25\MonitoramentoescalasController@getAllMonitoramentosByEscalaId');
        Route::get('monitoramentoescalas/{escala}', 'Api\App\v3_0_25\MonitoramentoescalasController@listaMonitoramento');
        Route::post('monitoramentoescalas', 'Api\App\v3_0_25\MonitoramentoescalasController@salvarMonitoramento');

        Route::post('prestadorFormacao/newPrestadorFormacao', 'Api\App\v3_0_25\PrestadorFormacaoController@newPrestadorFormacao');
        Route::delete('prestadorFormacao/{prestadorFormacao}', 'Api\App\v3_0_25\PrestadorFormacaoController@destroy');

        Route::get('pessoas/getPessoaPerfil', 'Api\App\v3_0_25\PessoasController@getPessoaPerfil');
        Route::put('pessoas/atualizaDadosPessoais/{pessoa}', 'Api\App\v3_0_25\PessoasController@atualizaDadosPessoais');

        Route::post('pessoaTelefones', 'Api\App\v3_0_25\PessoaTelefoneController@store');
        Route::delete('pessoaTelefones/{pessoaTelefone}', 'Api\App\v3_0_25\PessoaTelefoneController@destroy');

        Route::post('pessoaEmails', 'Api\App\v3_0_25\PessoaEmailController@store');
        Route::delete('pessoaEmails/{pessoaEmail}', 'Api\App\v3_0_25\PessoaEmailController@destroy');

        Route::post('pessoaEnderecos', 'Api\App\v3_0_25\PessoaEnderecoController@store');
        Route::put('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_0_25\PessoaEnderecoController@update');
        Route::delete('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_0_25\PessoaEnderecoController@destroy');

        Route::post('pontos/checkin/{escala}', 'Api\App\v3_0_25\PontosController@checkin'); // Custon
        Route::post('pontos/checkout/{escala}', 'Api\App\v3_0_25\PontosController@checkout'); // Custon
        Route::post('pontos/assinaturacheckout/{escala}', 'Api\App\v3_0_25\PontosController@assinaturacheckout'); // Custon

        Route::get('prestadores/meuspacientes', 'Api\App\v3_0_25\PrestadoresController@meuspacientes'); // Custon

        Route::get(
            'relatorios/{escala}',
            'Api\App\v3_0_25\RelatoriosController@listRelatoriosByEscalaId'
        );
        Route::get('relatorios/{escala}/list', 'Api\App\v3_0_25\RelatoriosController@getAllRelatoriosByEscalaId');
        Route::post('relatorios', 'Api\App\v3_0_25\RelatoriosController@store');
        Route::delete('relatorios/{relatorio}', 'Api\App\v3_0_25\RelatoriosController@destroy');

        Route::get('transcricoes/{ordemservico}', 'Api\App\v3_0_25\TranscricoesController@listTranscricoesByEscalaId');
        Route::get('pacientes/listMedicamentosByPaciente/{ordemservico}', 'Api\App\v3_0_25\TranscricoesController@listMedicamentosByPaciente');
        Route::get('chamados', 'Api\App\v3_0_25\ChamadosController@chamados');
        Route::get('get_pendencias', 'Api\App\v3_0_25\ChamadosController@get_pendencias');

        Route::post('criarchamado', 'Api\App\v3_0_25\ChamadosController@criarchamado');
        Route::post('enviarArquivos', 'Api\App\v3_0_25\ChamadosController@enviarArquivos');
    });
});

Route::prefix("app/v3_0_25")->group(function () {
    Route::get('cidades/listaCidadesCadastroApp/{uf}', [CidadesController::class, 'listaCidadesCadastroApp']);
    Route::post('versoes/verificarVersaoApp', [VersaoController::class, 'verificarVersaoApp']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix("app/v3_1_0")->group(function () {
        Route::post('acaomedicamentos', 'Api\App\v3_1_0\AcaomedicamentosController@store');

        Route::get('bancos', 'Api\App\v3_1_0\BancosController@index');

        Route::get('cidades/{uf}', 'Api\App\v3_1_0\CidadesController@index');

        Route::post('conselhos', 'Api\App\v3_1_0\ConselhosController@store');
        Route::put('conselhos/{conselho}', 'Api\App\v3_1_0\ConselhosController@update');
        Route::delete('conselhos/{conselho}', 'Api\App\v3_1_0\ConselhosController@destroy');

        Route::post('dadosbancarios', 'Api\App\v3_1_0\DadosbancariosController@store');
        Route::put('dadosbancarios/{dadosbancario}', 'Api\App\v3_1_0\DadosbancariosController@update');
        Route::delete('dadosbancarios/{dadosbancario}', 'Api\App\v3_1_0\DadosbancariosController@destroy');

        Route::get('empresaPrestador/meusContratos', 'Api\App\v3_1_0\EmpresaPrestadorController@index');
        Route::put('empresaPrestador/meusContratos/{empresaPrestador}', 'Api\App\v3_1_0\EmpresaPrestadorController@update');
        Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\App\v3_1_0\EmpresaPrestadorController@downloadFile');

        Route::get('escalas/listEscalasHoje', 'Api\App\v3_1_0\EscalasController@listEscalasHoje');
        Route::get('escalas/listEscalasMes', 'Api\App\v3_1_0\EscalasController@listEscalasMes');
        Route::get('escalas/getEscalaId/{escala}', 'Api\App\v3_1_0\EscalasController@getEscalaId');
        Route::get('escalas/getEscalaId/{escala}/cuidados', 'Api\App\v3_1_0\EscalasController@getCuidadosByEscalaId');

        Route::put('cuidadoEscalas/{cuidadoEscala}', 'Api\App\v3_1_0\CuidadoEscalasController@updateCuidado');

        Route::get('formacoes/listFormacoes', 'Api\App\v3_1_0\FormacoesController@listFormacoes');

        Route::post('especialidades', 'Api\App\v3_1_0\EspecialidadesController@store');
        Route::put('especialidades/{especialidade}', 'Api\App\v3_1_0\EspecialidadesController@update');
        Route::delete('especialidades/{especialidade}', 'Api\App\v3_1_0\EspecialidadesController@destroy');

        Route::get('monitoramentoescalas/{escala}/list', 'Api\App\v3_1_0\MonitoramentoescalasController@getAllMonitoramentosByEscalaId');
        Route::get('monitoramentoescalas/{escala}', 'Api\App\v3_1_0\MonitoramentoescalasController@listaMonitoramento');
        Route::post('monitoramentoescalas', 'Api\App\v3_1_0\MonitoramentoescalasController@salvarMonitoramento');

        Route::post('prestadorFormacao/newPrestadorFormacao', 'Api\App\v3_1_0\PrestadorFormacaoController@newPrestadorFormacao');
        Route::delete('prestadorFormacao/{prestadorFormacao}', 'Api\App\v3_1_0\PrestadorFormacaoController@destroy');

        Route::get('pessoas/getPessoaPerfil', 'Api\App\v3_1_0\PessoasController@getPessoaPerfil');
        Route::put('pessoas/atualizaDadosPessoais/{pessoa}', 'Api\App\v3_1_0\PessoasController@atualizaDadosPessoais');

        Route::post('pessoaTelefones', 'Api\App\v3_1_0\PessoaTelefoneController@store');
        Route::delete('pessoaTelefones/{pessoaTelefone}', 'Api\App\v3_1_0\PessoaTelefoneController@destroy');

        Route::post('pessoaEmails', 'Api\App\v3_1_0\PessoaEmailController@store');
        Route::delete('pessoaEmails/{pessoaEmail}', 'Api\App\v3_1_0\PessoaEmailController@destroy');

        Route::post('pessoaEnderecos', 'Api\App\v3_1_0\PessoaEnderecoController@store');
        Route::put('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_1_0\PessoaEnderecoController@update');
        Route::delete('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_1_0\PessoaEnderecoController@destroy');

        Route::post('pontos/checkin/{escala}', 'Api\App\v3_1_0\PontosController@checkin'); // Custon
        Route::post('pontos/checkout/{escala}', 'Api\App\v3_1_0\PontosController@checkout'); // Custon
        Route::post('pontos/assinaturacheckout/{escala}', 'Api\App\v3_1_0\PontosController@assinaturacheckout'); // Custon

        Route::get('prestadores/meuspacientes', 'Api\App\v3_1_0\PrestadoresController@meuspacientes'); // Custon

        Route::get('relatorios/{escala}', 'Api\App\v3_1_0\RelatoriosController@listRelatoriosByEscalaId');
        Route::get('relatorios/{escala}/list', 'Api\App\v3_1_0\RelatoriosController@getAllRelatoriosByEscalaId');
        Route::post('relatorios', 'Api\App\v3_1_0\RelatoriosController@store');
        Route::delete('relatorios/{relatorio}', 'Api\App\v3_1_0\RelatoriosController@destroy');

        Route::get('transcricoes/{ordemservico}', 'Api\App\v3_1_0\TranscricoesController@listTranscricoesByEscalaId');
        Route::get('pacientes/listMedicamentosByPaciente/{ordemservico}', 'Api\App\v3_1_0\TranscricoesController@listMedicamentosByPaciente');
        Route::get('chamados', 'Api\App\v3_1_0\ChamadosController@chamados');
        Route::get('get_pendencias', 'Api\App\v3_1_0\ChamadosController@get_pendencias');

        Route::post('criarchamado', 'Api\App\v3_1_0\ChamadosController@criarchamado');
        // Route::post('enviarArquivos', 'Api\App\v3_1_0\ChamadosController@enviarArquivos');
    });
});

Route::prefix("app/v3_1_0")->group(function () {
    Route::post('enviarArquivos', 'Api\App\v3_1_0\ChamadosController@enviarArquivos');
    Route::post('versoes/verificarVersaoApp', 'Api\App\v3_1_0\VersaoController@verificarVersaoApp');
    Route::get('cidades/listaCidadesCadastroApp/{uf}', 'Api\App\v3_1_0\CidadesController@index');
    Route::post('enviarArquivos', 'Api\App\v3_1_0\ChamadosController@enviarArquivos');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix("app/v3_1_3")->group(function () {
        Route::post('acaomedicamentos', 'Api\App\v3_1_3\AcaomedicamentosController@store');

        Route::get('bancos', 'Api\App\v3_1_3\BancosController@index');

        Route::get('cidades/{uf}', 'Api\App\v3_1_3\CidadesController@index');

        Route::post('conselhos', 'Api\App\v3_1_3\ConselhosController@store');
        Route::put('conselhos/{conselho}', 'Api\App\v3_1_3\ConselhosController@update');
        Route::delete('conselhos/{conselho}', 'Api\App\v3_1_3\ConselhosController@destroy');

        Route::post('dadosbancarios', 'Api\App\v3_1_3\DadosbancariosController@store');
        Route::put('dadosbancarios/{dadosbancario}', 'Api\App\v3_1_3\DadosbancariosController@update');
        Route::delete('dadosbancarios/{dadosbancario}', 'Api\App\v3_1_3\DadosbancariosController@destroy');

        Route::get('empresaPrestador/meusContratos', 'Api\App\v3_1_3\EmpresaPrestadorController@index');
        Route::put('empresaPrestador/meusContratos/{empresaPrestador}', 'Api\App\v3_1_3\EmpresaPrestadorController@update');
        Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\App\v3_1_3\EmpresaPrestadorController@downloadFile');

        Route::get('escalas/listEscalasHoje', 'Api\App\v3_1_3\EscalasController@listEscalasHoje');
        Route::get('escalas/listEscalasMes', 'Api\App\v3_1_3\EscalasController@listEscalasMes');
        Route::get('escalas/getEscalaId/{escala}', 'Api\App\v3_1_3\EscalasController@getEscalaId');
        Route::get('escalas/getEscalaId/{escala}/cuidados', 'Api\App\v3_1_3\EscalasController@getCuidadosByEscalaId');

        Route::put('cuidadoEscalas/{cuidadoEscala}', 'Api\App\v3_1_3\CuidadoEscalasController@updateCuidado');

        Route::get('formacoes/listFormacoes', 'Api\App\v3_1_3\FormacoesController@listFormacoes');

        Route::post('especialidades', 'Api\App\v3_1_3\EspecialidadesController@store');
        Route::put('especialidades/{especialidade}', 'Api\App\v3_1_3\EspecialidadesController@update');
        Route::delete('especialidades/{especialidade}', 'Api\App\v3_1_3\EspecialidadesController@destroy');

        Route::get('monitoramentoescalas/{escala}/list', 'Api\App\v3_1_3\MonitoramentoescalasController@getAllMonitoramentosByEscalaId');
        Route::get('monitoramentoescalas/{escala}', 'Api\App\v3_1_3\MonitoramentoescalasController@listaMonitoramento');
        Route::post('monitoramentoescalas', 'Api\App\v3_1_3\MonitoramentoescalasController@salvarMonitoramento');

        Route::post('prestadorFormacao/newPrestadorFormacao', 'Api\App\v3_1_3\PrestadorFormacaoController@newPrestadorFormacao');
        Route::delete('prestadorFormacao/{prestadorFormacao}', 'Api\App\v3_1_3\PrestadorFormacaoController@destroy');

        Route::get('pessoas/getPessoaPerfil', 'Api\App\v3_1_3\PessoasController@getPessoaPerfil');
        Route::put('pessoas/atualizaDadosPessoais/{pessoa}', 'Api\App\v3_1_3\PessoasController@atualizaDadosPessoais');

        Route::post('pessoaTelefones', 'Api\App\v3_1_3\PessoaTelefoneController@store');
        Route::delete('pessoaTelefones/{pessoaTelefone}', 'Api\App\v3_1_3\PessoaTelefoneController@destroy');

        Route::post('pessoaEmails', 'Api\App\v3_1_3\PessoaEmailController@store');
        Route::delete('pessoaEmails/{pessoaEmail}', 'Api\App\v3_1_3\PessoaEmailController@destroy');

        Route::post('pessoaEnderecos', 'Api\App\v3_1_3\PessoaEnderecoController@store');
        Route::put('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_1_3\PessoaEnderecoController@update');
        Route::delete('pessoaEnderecos/{pessoaEndereco}', 'Api\App\v3_1_3\PessoaEnderecoController@destroy');

        Route::post('pontos/checkin/{escala}', 'Api\App\v3_1_3\PontosController@checkin'); // Custon
        Route::post('pontos/checkout/{escala}', 'Api\App\v3_1_3\PontosController@checkout'); // Custon
        Route::post('pontos/assinaturacheckout/{escala}', 'Api\App\v3_1_3\PontosController@assinaturacheckout'); // Custon

        Route::get('prestadores/meuspacientes', 'Api\App\v3_1_3\PrestadoresController@meuspacientes'); // Custon

        Route::get('relatorios/{escala}', 'Api\App\v3_1_3\RelatoriosController@listRelatoriosByEscalaId');
        Route::get('relatorios/{escala}/list', 'Api\App\v3_1_3\RelatoriosController@getAllRelatoriosByEscalaId');
        Route::post('relatorios', 'Api\App\v3_1_3\RelatoriosController@store');
        Route::delete('relatorios/{relatorio}', 'Api\App\v3_1_3\RelatoriosController@destroy');

        Route::get('transcricoes/{ordemservico}', 'Api\App\v3_1_3\TranscricoesController@listTranscricoesByEscalaId');
        Route::get('pacientes/listMedicamentosByPaciente/{ordemservico}', 'Api\App\v3_1_3\TranscricoesController@listMedicamentosByPaciente');
        Route::get('chamados', 'Api\App\v3_1_3\ChamadosController@chamados');
        Route::get('get_pendencias', 'Api\App\v3_1_3\ChamadosController@get_pendencias');

        Route::post('criarchamado', 'Api\App\v3_1_3\ChamadosController@criarchamado');
    });
});
Route::prefix("app/v3_1_3")->group(function () {
    Route::post('enviarArquivos', 'Api\App\v3_1_3\ChamadosController@enviarArquivos');
    Route::post('versoes/verificarVersaoApp', 'Api\App\v3_1_3\VersaoController@verificarVersaoApp');
    Route::get('cidades/listaCidadesCadastroApp/{uf}', 'Api\App\v3_1_3\CidadesController@index');
});
