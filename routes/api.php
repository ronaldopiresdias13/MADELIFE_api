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
    Route::apiResource('emails'     , 'Api\EmailsController'     );
    Route::apiResource('acessos'    , 'Api\AcessosController'    );
    Route::apiResource('users'      , 'Api\UsersController'      );
    Route::apiResource('bancos'     , 'Api\BancosController'     );
    Route::apiResource('cargos'     , 'Api\CargosController'     );
    Route::apiResource('empresas'   , 'Api\EmpresasController'   );
    Route::apiResource('cidades'    , 'Api\CidadesController'    );
    Route::apiResource('clientes'   , 'Api\ClientesController'   );
    Route::apiResource('prestadores', 'Api\PrestadoresController');
    Route::apiResource('servicos'   , 'Api\ServicosController'   );
    Route::apiResource('cuidados'   , 'Api\CuidadosController'   );
    Route::apiResource('pessoas'    , 'Api\PessoasController'    );
    Route::post('prestadores/migracao', 'Api\PrestadoresController@migracao');
    Route::post('clientes/migracao', 'Api\ClientesController@migracao');
    Route::post('orcamentos/migracao', 'Api\OrcamentoController@migracao');
    Route::post('cuidados/migracao', 'Api\CuidadosController@migracao');







// });

Route::post("/image" , "Controller@uploadimage");
