<?php

use App\Http\Controllers\Web\Prestadores\PrestadoresController;
use App\Http\Controllers\Api\EmpresaPrestadorController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('web/areaClinica/profissionais/historicopacientesprestador/{prestador}', 'Api\Web\PrestadoresController@historicopacientesprestador');
    Route::get('web/prestadores/recrutamento', [PrestadoresController::class, 'listRecrutamento']);
    Route::get('web/prestadores/empresaPrestador/listaPrestadoresPorEmpresaIdEStatus', [EmpresaPrestadorController::class, 'listaPrestadoresPorEmpresaIdEStatus']);
    Route::get('web/prestadores/buscaPrestadorComServicosPrestadosNaEmpresa/{prestador}', [PrestadoresController::class, 'buscaPrestadorComServicosPrestadosNaEmpresa']);
    Route::get('web/prestadores/buscaprestadorexterno/{prestador}', [PrestadoresController::class, 'buscaprestadorexterno']);
    Route::get('web/prestadores/listNomePrestadores', 'Api\Web\PrestadoresController@listNomePrestadores');
    Route::get('web/prestadores/listPrestadoresComFormacoes', 'Api\Web\PrestadoresController@listPrestadoresComFormacoes');
    Route::get('web/prestadores/buscaPrestadoresPorCliente', [PrestadoresController::class, 'buscaPrestadoresPorCliente']);
});

Route::get('prestadores/atribuicao', 'Api\Web\PrestadoresController@buscaprestadoresporfiltro'); // MUDAR AQUII DEPOIS

Route::get('prestadores', 'Api\PrestadoresController@index');
Route::post('prestadores', 'Api\PrestadoresController@store');
Route::get('prestadores/{prestador}', 'Api\PrestadoresController@show');
Route::put('prestadores/{prestador}', 'Api\PrestadoresController@update');
Route::delete('prestadores/{prestador}', 'Api\PrestadoresController@destroy');
Route::get('prestadores/{prestador}/meuspacientes', 'Api\PrestadoresController@meuspacientes'); // Custon
