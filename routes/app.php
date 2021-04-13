<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:api'], function () {
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
    Route::prefix("app/v3_0_21")->group(function () {
        Route::post('acaomedicamentos', 'Api\App\v3_0_21\AcaomedicamentosController@store');

        Route::get('bancos', 'Api\App\v3_0_21\BancosController@index');

        Route::get('cidades/{uf}', 'Api\App\v3_0_21\CidadeController@index');

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
        Route::post('criarchamado', 'Api\App\v3_0_21\ChamadosController@criarchamado');
        Route::post('enviarArquivos', 'Api\App\v3_0_21\ChamadosController@enviarArquivos');
    });
    Route::prefix("app/v3_0_22")->group(function () {
        Route::post('acaomedicamentos', 'Api\App\v3_0_22\AcaomedicamentosController@store');

        Route::get('bancos', 'Api\App\v3_0_22\BancosController@index');

        Route::get('cidades/{uf}', 'Api\App\v3_0_22\CidadeController@index');

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
        Route::post('criarchamado', 'Api\App\v3_0_22\ChamadosController@criarchamado');
        Route::post('enviarArquivos', 'Api\App\v3_0_22\ChamadosController@enviarArquivos');
    });
});
