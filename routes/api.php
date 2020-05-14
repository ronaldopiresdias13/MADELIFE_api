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
    
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'Auth\AuthController@logout');
        Route::get('user'  , 'Auth\AuthController@user'  );
    });
});

// Route::group(['middleware' => 'auth:api'], function() {
    Route::apiResource('acessos'                , 'Api\AcessosController'                );
    Route::apiResource('bancos'                 , 'Api\BancosController'                 );
    Route::apiResource('beneficios'             , 'Api\BeneficiosController'             );
    Route::apiResource('cargos'                 , 'Api\CargosController'                 );
    Route::apiResource('categorianaturezas'     , 'Api\CategorianaturezasController'     );
    Route::apiResource('cidades'                , 'Api\CidadesController'                );
    Route::apiResource('clientes'               , 'Api\ClientesController'               );
    Route::apiResource('conselhos'              , 'Api\ConselhosController'              );
    Route::apiResource('convenios'              , 'Api\ConveniosController'              );
    Route::apiResource('cuidados'               , 'Api\CuidadosController'               );
    Route::apiResource('dadosbancarios'         , 'Api\DadosbancariosController'         );
    Route::apiResource('dadoscontratuais'       , 'Api\DadoscontratuaisController'       );
    Route::apiResource('diagnosticossecundarios', 'Api\DiagnosticossecundariosController');
    Route::apiResource('emails'                 , 'Api\EmailsController'                 );
    Route::apiResource('empresas'               , 'Api\EmpresasController'               );
    Route::apiResource('enderecos'              , 'Api\EnderecosController'              );
    Route::apiResource('formacoes'              , 'Api\FormacoesController'              );
    Route::apiResource('fornecedores'           , 'Api\FornecedoresController'           );
    Route::apiResource('grupocuidados'          , 'Api\GrupocuidadosController'          );
    Route::apiResource('horariostrabalho'       , 'Api\HorariostrabalhoController'       );
    Route::apiResource('marcas'                 , 'Api\MarcasController'                 );
    Route::apiResource('orcamentos'             , 'Api\OrcamentosController'             );
    Route::apiResource('ordemservicos'          , 'Api\OrdemservicosController'          );
    Route::apiResource('outros'                 , 'Api\OutrosController'                 );
    Route::apiResource('pacientes'              , 'Api\PacientesController'              );
    Route::apiResource('pessoas'                , 'Api\PessoasController'                );
    Route::apiResource('pils'                   , 'Api\PilsController'                   );
    Route::apiResource('presceicoesbs'          , 'Api\PrescricoesbsController'          );
    Route::apiResource('prestadores'            , 'Api\PrestadoresController'            );
    Route::apiResource('produtos'               , 'Api\ProdutosController'               );
    Route::apiResource('profissionais'          , 'Api\ProfissionaisController'          );
    Route::apiResource('responsaveis'           , 'Api\ResponsaveisController'           );
    Route::apiResource('servicos'               , 'Api\ServicosController'               );
    Route::apiResource('setores'                , 'Api\SetoresController'                );
    Route::apiResource('telefones'              , 'Api\TelefonesController'              );
    Route::apiResource('tipoprodutos'           , 'Api\TipoprodutosController'           );
    Route::apiResource('transcricoes'           , 'Api\TranscricoesController'           );
    Route::apiResource('unidadesmedida'         , 'Api\UnidadesmedidaController'         );
    Route::apiResource('users'                  , 'Api\UsersController'                  );

    Route::post('prestadores/migracao'   , 'Api\PrestadoresController@migracao'   );
    Route::post('clientes/migracao'      , 'Api\ClientesController@migracao'      );
    Route::post('orcamentos/migracao'    , 'Api\OrcamentosController@migracao'    );
    Route::post('cuidados/migracao'      , 'Api\CuidadosController@migracao'      );
    Route::post('grupocuidados/migracao' , 'Api\GrupocuidadosController@migracao' );
    Route::post('fornecedores/migracao'  , 'Api\FornecedoresController@migracao'  );
    Route::post('unidadesmedida/migracao', 'Api\UnidadesmedidaController@migracao');
    Route::post('tipoprodutos/migracao'  , 'Api\TipoprodutosController@migracao'  );
    Route::post('produtos/migracao'      , 'Api\ProdutosController@migracao'      );
    Route::post('ordemservicos/migracao' , 'Api\OrdemservicosController@migracao' );
    Route::post('profissionais/migracao' , 'Api\ProfissionaisController@migracao' );







// });

Route::post("/image" , "Controller@uploadimage");
