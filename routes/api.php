<?php

use Illuminate\Http\Request;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login'   , 'Auth\AuthController@login'   )->name('login');
    Route::post('register', 'Auth\AuthController@register')               ;
    Route::post('reset'   , 'Auth\AuthController@reset'   )               ;
    Route::post('change'  , 'Auth\AuthController@change'  )               ;
    
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'Auth\AuthController@logout');
        Route::get('user'  , 'Auth\AuthController@user'  );
    });
});

Route::post('prestadores/migracao'   , 'Api\PrestadoresController@migracao'   );
Route::post('clientes/migracao'      , 'Api\ClientesController@migracao'      );
Route::post('orcamentos/migracao'    , 'Api\OrcamentosController@migracao'    );
Route::post('cuidados/migracao'      , 'Api\CuidadosController@migracao'      );
Route::post('grupocuidados/migracao' , 'Api\GrupocuidadosController@migracao' );
Route::post('fornecedores/migracao'  , 'Api\FornecedoresController@migracao'  );
Route::post('unidadesmedida/migracao', 'Api\UnidademedidasController@migracao');
Route::post('tipoprodutos/migracao'  , 'Api\TipoprodutosController@migracao'  );
Route::post('produtos/migracao'      , 'Api\ProdutosController@migracao'      );
Route::post('ordemservicos/migracao' , 'Api\OrdemservicosController@migracao' );
Route::post('profissionais/migracao' , 'Api\ProfissionaisController@migracao' );
Route::post('escalas/migracao'       , 'Api\EscalasController@migracao'       );

Route::post("/image" , "Controller@uploadimage");

// Route::group(['middleware' => 'auth:api'], function() { // Descomentar para utilizar Autenticação via Token

    Route::get   ('acessos'         , 'Api\AcessosController@index'  );
    Route::post  ('acessos'         , 'Api\AcessosController@store'  );
    Route::get   ('acessos/{acesso}', 'Api\AcessosController@show'   );
    Route::put   ('acessos/{acesso}', 'Api\AcessosController@update' );
    Route::delete('acessos/{acesso}', 'Api\AcessosController@destroy');

    Route::get   ('bancos'        , 'Api\BancosController@index'  );
    Route::post  ('bancos'        , 'Api\BancosController@store'  );
    Route::get   ('bancos/{banco}', 'Api\BancosController@show'   );
    Route::put   ('bancos/{banco}', 'Api\BancosController@update' );
    Route::delete('bancos/{banco}', 'Api\BancosController@destroy');

    Route::get   ('beneficios'            , 'Api\BeneficiosController@index'  );
    Route::post  ('beneficios'            , 'Api\BeneficiosController@store'  );
    Route::get   ('beneficios/{beneficio}', 'Api\BeneficiosController@show'   );
    Route::put   ('beneficios/{beneficio}', 'Api\BeneficiosController@update' );
    Route::delete('beneficios/{beneficio}', 'Api\BeneficiosController@destroy');

    Route::get   ('cargos'        , 'Api\CargosController@index'  );
    Route::post  ('cargos'        , 'Api\CargosController@store'  );
    Route::get   ('cargos/{cargo}', 'Api\CargosController@show'   );
    Route::put   ('cargos/{cargo}', 'Api\CargosController@update' );
    Route::delete('cargos/{cargo}', 'Api\CargosController@destroy');

    Route::get   ('categorianaturezas'                    , 'Api\CategorianaturezasController@index'  );
    Route::post  ('categorianaturezas'                    , 'Api\CategorianaturezasController@store'  );
    Route::get   ('categorianaturezas/{categorianatureza}', 'Api\CategorianaturezasController@show'   );
    Route::put   ('categorianaturezas/{categorianatureza}', 'Api\CategorianaturezasController@update' );
    Route::delete('categorianaturezas/{categorianatureza}', 'Api\CategorianaturezasController@destroy');

    Route::get   ('cidades'         , 'Api\CidadesController@index'  );
    Route::post  ('cidades'         , 'Api\CidadesController@store'  );
    Route::get   ('cidades/{cidade}', 'Api\CidadesController@show'   );
    Route::put   ('cidades/{cidade}', 'Api\CidadesController@update' );
    Route::delete('cidades/{cidade}', 'Api\CidadesController@destroy');

    Route::get   ('clientes'          , 'Api\ClientesController@index'  );
    Route::post  ('clientes'          , 'Api\ClientesController@store'  );
    Route::get   ('clientes/{cliente}', 'Api\ClientesController@show'   );
    Route::put   ('clientes/{cliente}', 'Api\ClientesController@update' );
    Route::delete('clientes/{cliente}', 'Api\ClientesController@destroy');

    Route::get   ('conselhos'           , 'Api\ConselhosController@index'  );
    Route::post  ('conselhos'           , 'Api\ConselhosController@store'  );
    Route::get   ('conselhos/{conselho}', 'Api\ConselhosController@show'   );
    Route::put   ('conselhos/{conselho}', 'Api\ConselhosController@update' );
    Route::delete('conselhos/{conselho}', 'Api\ConselhosController@destroy');

    Route::get   ('convenios'           , 'Api\ConveniosController@index'  );
    Route::post  ('convenios'           , 'Api\ConveniosController@store'  );
    Route::get   ('convenios/{convenio}', 'Api\ConveniosController@show'   );
    Route::put   ('convenios/{convenio}', 'Api\ConveniosController@update' );
    Route::delete('convenios/{convenio}', 'Api\ConveniosController@destroy');

    Route::get   ('cuidados'          , 'Api\CuidadosController@index'  );
    Route::post  ('cuidados'          , 'Api\CuidadosController@store'  );
    Route::get   ('cuidados/{cuidado}', 'Api\CuidadosController@show'   );
    Route::put   ('cuidados/{cuidado}', 'Api\CuidadosController@update' );
    Route::delete('cuidados/{cuidado}', 'Api\CuidadosController@destroy');

    Route::get   ('dadosbancarios'                , 'Api\DadosbancariosController@index'  );
    Route::post  ('dadosbancarios'                , 'Api\DadosbancariosController@store'  );
    Route::get   ('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@show'   );
    Route::put   ('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@update' );
    Route::delete('dadosbancarios/{dadosbancario}', 'Api\DadosbancariosController@destroy');

    Route::get   ('dadoscontratuais'                  , 'Api\DadoscontratuaisController@index'  );
    Route::post  ('dadoscontratuais'                  , 'Api\DadoscontratuaisController@store'  );
    Route::get   ('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@show'   );
    Route::put   ('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@update' );
    Route::delete('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@destroy');

    Route::get   ('diagnosticossecundarios'                         , 'Api\DiagnosticossecundariosController@index'  );
    Route::post  ('diagnosticossecundarios'                         , 'Api\DiagnosticossecundariosController@store'  );
    Route::get   ('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@show'   );
    Route::put   ('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@update' );
    Route::delete('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@destroy');

    Route::get   ('emails'        , 'Api\EmailsController@index'  );
    Route::post  ('emails'        , 'Api\EmailsController@store'  );
    Route::get   ('emails/{email}', 'Api\EmailsController@show'   );
    Route::put   ('emails/{email}', 'Api\EmailsController@update' );
    Route::delete('emails/{email}', 'Api\EmailsController@destroy');

    Route::get   ('empresas'          , 'Api\EmpresasController@index'  );
    Route::post  ('empresas'          , 'Api\EmpresasController@store'  );
    Route::get   ('empresas/{empresa}', 'Api\EmpresasController@show'   );
    Route::put   ('empresas/{empresa}', 'Api\EmpresasController@update' );
    Route::delete('empresas/{empresa}', 'Api\EmpresasController@destroy');

    Route::get   ('enderecos'           , 'Api\EnderecosController@index'  );
    Route::post  ('enderecos'           , 'Api\EnderecosController@store'  );
    Route::get   ('enderecos/{endereco}', 'Api\EnderecosController@show'   );
    Route::put   ('enderecos/{endereco}', 'Api\EnderecosController@update' );
    Route::delete('enderecos/{endereco}', 'Api\EnderecosController@destroy');

    Route::get   ('escalas'         , 'Api\EscalasController@index'  );
    Route::post  ('escalas'         , 'Api\EscalasController@store'  );
    Route::get   ('escalas/{escala}', 'Api\EscalasController@show'   );
    Route::put   ('escalas/{escala}', 'Api\EscalasController@update' );
    Route::delete('escalas/{escala}', 'Api\EscalasController@destroy');

    Route::get   ('formacoes'           , 'Api\FormacoesController@index'  );
    Route::post  ('formacoes'           , 'Api\FormacoesController@store'  );
    Route::get   ('formacoes/{formacao}', 'Api\FormacoesController@show'   );
    Route::put   ('formacoes/{formacao}', 'Api\FormacoesController@update' );
    Route::delete('formacoes/{formacao}', 'Api\FormacoesController@destroy');

    Route::get   ('fornecedores'             , 'Api\FornecedoresController@index'  );
    Route::post  ('fornecedores'             , 'Api\FornecedoresController@store'  );
    Route::get   ('fornecedores/{fornecedor}', 'Api\FornecedoresController@show'   );
    Route::put   ('fornecedores/{fornecedor}', 'Api\FornecedoresController@update' );
    Route::delete('fornecedores/{fornecedor}', 'Api\FornecedoresController@destroy');

    Route::get   ('grupocuidados'               , 'Api\GrupocuidadosController@index'  );
    Route::post  ('grupocuidados'               , 'Api\GrupocuidadosController@store'  );
    Route::get   ('grupocuidados/{grupocuidado}', 'Api\GrupocuidadosController@show'   );
    Route::put   ('grupocuidados/{grupocuidado}', 'Api\GrupocuidadosController@update' );
    Route::delete('grupocuidados/{grupocuidado}', 'Api\GrupocuidadosController@destroy');

    Route::get   ('horariostrabalho'                   , 'Api\HorariostrabalhoController@index'  );
    Route::post  ('horariostrabalho'                   , 'Api\HorariostrabalhoController@store'  );
    Route::get   ('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@show'   );
    Route::put   ('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@update' );
    Route::delete('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@destroy');

    Route::get   ('historicoorcamentos'                     , 'Api\HistoricoorcamentosController@index'  );
    Route::post  ('historicoorcamentos'                     , 'Api\HistoricoorcamentosController@store'  );
    Route::get   ('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@show'   );
    Route::put   ('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@update' );
    Route::delete('historicoorcamentos/{historicoorcamento}', 'Api\HistoricoorcamentosController@destroy');

    Route::get   ('impostos'          , 'Api\ImpostosController@index'  );
    Route::post  ('impostos'          , 'Api\ImpostosController@store'  );
    Route::get   ('impostos/{imposto}', 'Api\ImpostosController@show'   );
    Route::put   ('impostos/{imposto}', 'Api\ImpostosController@update' );
    Route::delete('impostos/{imposto}', 'Api\ImpostosController@destroy');

    Route::get   ('marcas'        , 'Api\MarcasController@index'  );
    Route::post  ('marcas'        , 'Api\MarcasController@store'  );
    Route::get   ('marcas/{marca}', 'Api\MarcasController@show'   );
    Route::put   ('marcas/{marca}', 'Api\MarcasController@update' );
    Route::delete('marcas/{marca}', 'Api\MarcasController@destroy');

    Route::get   ('orcamentos'            , 'Api\OrcamentosController@index'  );
    Route::post  ('orcamentos'            , 'Api\OrcamentosController@store'  );
    Route::get   ('orcamentos/{orcamento}', 'Api\OrcamentosController@show'   );
    Route::put   ('orcamentos/{orcamento}', 'Api\OrcamentosController@update' );
    Route::delete('orcamentos/{orcamento}', 'Api\OrcamentosController@destroy');

    Route::get   ('orcamentocustos'                 , 'Api\OrcamentoCustosController@index'  );
    Route::post  ('orcamentocustos'                 , 'Api\OrcamentoCustosController@store'  );
    Route::get   ('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@show'   );
    Route::put   ('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@update' );
    Route::delete('orcamentocustos/{orcamentocusto}', 'Api\OrcamentoCustosController@destroy');

    Route::get   ('orcamentoprodutos'                   , 'Api\OrcamentoProdutosController@index'  );
    Route::post  ('orcamentoprodutos'                   , 'Api\OrcamentoProdutosController@store'  );
    Route::get   ('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@show'   );
    Route::put   ('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@update' );
    Route::delete('orcamentoprodutos/{orcamentoproduto}', 'Api\OrcamentoProdutosController@destroy');

    Route::get   ('orcamentoservicos'                   , 'Api\OrcamentoServicosController@index'  );
    Route::post  ('orcamentoservicos'                   , 'Api\OrcamentoServicosController@store'  );
    Route::get   ('orcamentoservicos/{orcamentoservico}', 'Api\OrcamentoServicosController@show'   );
    Route::put   ('orcamentoservicos/{orcamentoservico}', 'Api\OrcamentoServicosController@update' );
    Route::delete('orcamentoservicos/{orcamentoservico}', 'Api\OrcamentoServicosController@destroy');

    Route::get   ('ordemservicos'               , 'Api\OrdemservicosController@index'  );
    Route::post  ('ordemservicos'               , 'Api\OrdemservicosController@store'  );
    Route::get   ('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@show'   );
    Route::put   ('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@update' );
    Route::delete('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@destroy');

    Route::get   ('outros'        , 'Api\OutrosController@index'  );
    Route::post  ('outros'        , 'Api\OutrosController@store'  );
    Route::get   ('outros/{outro}', 'Api\OutrosController@show'   );
    Route::put   ('outros/{outro}', 'Api\OutrosController@update' );
    Route::delete('outros/{outro}', 'Api\OutrosController@destroy');

    Route::get   ('pacientes'           , 'Api\PacientesController@index'  );
    Route::post  ('pacientes'           , 'Api\PacientesController@store'  );
    Route::get   ('pacientes/{paciente}', 'Api\PacientesController@show'   );
    Route::put   ('pacientes/{paciente}', 'Api\PacientesController@update' );
    Route::delete('pacientes/{paciente}', 'Api\PacientesController@destroy');

    Route::get   ('pessoas'         , 'Api\PessoasController@index'  );
    Route::post  ('pessoas'         , 'Api\PessoasController@store'  );
    Route::get   ('pessoas/{pessoa}', 'Api\PessoasController@show'   );
    Route::put   ('pessoas/{pessoa}', 'Api\PessoasController@update' );
    Route::delete('pessoas/{pessoa}', 'Api\PessoasController@destroy');

    Route::get   ('pils'      , 'Api\PilsController@index'  );
    Route::post  ('pils'      , 'Api\PilsController@store'  );
    Route::get   ('pils/{pil}', 'Api\PilsController@show'   );
    Route::put   ('pils/{pil}', 'Api\PilsController@update' );
    Route::delete('pils/{pil}', 'Api\PilsController@destroy');

    Route::get   ('prescricoesbs'              , 'Api\PrescricoesbsController@index'  );
    Route::post  ('prescricoesbs'              , 'Api\PrescricoesbsController@store'  );
    Route::get   ('prescricoesbs/{prescricaob}', 'Api\PrescricoesbsController@show'   );
    Route::put   ('prescricoesbs/{prescricaob}', 'Api\PrescricoesbsController@update' );
    Route::delete('prescricoesbs/{prescricaob}', 'Api\PrescricoesbsController@destroy');

    Route::get   ('prestadores'            , 'Api\PrestadoresController@index'  );
    Route::post  ('prestadores'            , 'Api\PrestadoresController@store'  );
    Route::get   ('prestadores/{prestador}', 'Api\PrestadoresController@show'   );
    Route::put   ('prestadores/{prestador}', 'Api\PrestadoresController@update' );
    Route::delete('prestadores/{prestador}', 'Api\PrestadoresController@destroy');

    Route::get   ('produtos'          , 'Api\ProdutosController@index'  );
    Route::post  ('produtos'          , 'Api\ProdutosController@store'  );
    Route::get   ('produtos/{produto}', 'Api\ProdutosController@show'   );
    Route::put   ('produtos/{produto}', 'Api\ProdutosController@update' );
    Route::delete('produtos/{produto}', 'Api\ProdutosController@destroy');

    Route::get   ('profissionais'               , 'Api\ProfissionaisController@index'  );
    Route::post  ('profissionais'               , 'Api\ProfissionaisController@store'  );
    Route::get   ('profissionais/{profissional}', 'Api\ProfissionaisController@show'   );
    Route::put   ('profissionais/{profissional}', 'Api\ProfissionaisController@update' );
    Route::delete('profissionais/{profissional}', 'Api\ProfissionaisController@destroy');

    Route::get   ('responsaveis'              , 'Api\ResponsaveisController@index'  );
    Route::post  ('responsaveis'              , 'Api\ResponsaveisController@store'  );
    Route::get   ('responsaveis/{responsavel}', 'Api\ResponsaveisController@show'   );
    Route::put   ('responsaveis/{responsavel}', 'Api\ResponsaveisController@update' );
    Route::delete('responsaveis/{responsavel}', 'Api\ResponsaveisController@destroy');

    Route::get   ('servicos'          , 'Api\ServicosController@index'  );
    Route::post  ('servicos'          , 'Api\ServicosController@store'  );
    Route::get   ('servicos/{servico}', 'Api\ServicosController@show'   );
    Route::put   ('servicos/{servico}', 'Api\ServicosController@update' );
    Route::delete('servicos/{servico}', 'Api\ServicosController@destroy');

    Route::get   ('setores'        , 'Api\SetoresController@index'  );
    Route::post  ('setores'        , 'Api\SetoresController@store'  );
    Route::get   ('setores/{setor}', 'Api\SetoresController@show'   );
    Route::put   ('setores/{setor}', 'Api\SetoresController@update' );
    Route::delete('setores/{setor}', 'Api\SetoresController@destroy');

    Route::get   ('telefones'           , 'Api\TelefonesController@index'  );
    Route::post  ('telefones'           , 'Api\TelefonesController@store'  );
    Route::get   ('telefones/{telefone}', 'Api\TelefonesController@show'   );
    Route::put   ('telefones/{telefone}', 'Api\TelefonesController@update' );
    Route::delete('telefones/{telefone}', 'Api\TelefonesController@destroy');

    Route::get   ('tipoprodutos'              , 'Api\TipoprodutosController@index'  );
    Route::post  ('tipoprodutos'              , 'Api\TipoprodutosController@store'  );
    Route::get   ('tipoprodutos/{tipoproduto}', 'Api\TipoprodutosController@show'   );
    Route::put   ('tipoprodutos/{tipoproduto}', 'Api\TipoprodutosController@update' );
    Route::delete('tipoprodutos/{tipoproduto}', 'Api\TipoprodutosController@destroy');

    Route::get   ('transcricoes'              , 'Api\TranscricoesController@index'  );
    Route::post  ('transcricoes'              , 'Api\TranscricoesController@store'  );
    Route::get   ('transcricoes/{transcricao}', 'Api\TranscricoesController@show'   );
    Route::put   ('transcricoes/{transcricao}', 'Api\TranscricoesController@update' );
    Route::delete('transcricoes/{transcricao}', 'Api\TranscricoesController@destroy');

    Route::get   ('unidademedidas'                , 'Api\UnidademedidasController@index'  );
    Route::post  ('unidademedidas'                , 'Api\UnidademedidasController@store'  );
    Route::get   ('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@show'   );
    Route::put   ('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@update' );
    Route::delete('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@destroy');

    Route::get   ('users'       , 'Api\UsersController@index'  );
    Route::post  ('users'       , 'Api\UsersController@store'  );
    Route::get   ('users/{user}', 'Api\UsersController@show'   );
    Route::put   ('users/{user}', 'Api\UsersController@update' );
    Route::delete('users/{user}', 'Api\UsersController@destroy');

// }); // Descomentar para utilizar Autenticação via Token