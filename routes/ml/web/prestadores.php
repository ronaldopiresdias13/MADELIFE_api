<?php

use App\Http\Controllers\Web\Prestadores\PrestadoresController;
use App\Http\Controllers\Api\EmpresaPrestadorController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('areaClinica')->group(function () {
            Route::get('profissionais/historicopacientesprestador/{prestador}', 'Api\Web\PrestadoresController@historicopacientesprestador');
        });
        Route::prefix('prestadores')->group(function () {
            Route::get('recrutamento', [PrestadoresController::class, 'listRecrutamento']);
            Route::get('empresaPrestador/listaPrestadoresPorEmpresaIdEStatus', [EmpresaPrestadorController::class, 'listaPrestadoresPorEmpresaIdEStatus']);
            Route::get('buscaprestadorexterno/{prestador}', [PrestadoresController::class, 'buscaprestadorexterno']);
        });
        Route::get('prestadores/listNomePrestadores', 'Api\Web\PrestadoresController@listNomePrestadores');
        Route::get('prestadores/listPrestadoresComFormacoes', 'Api\Web\PrestadoresController@listPrestadoresComFormacoes');
    });
});

Route::get('prestadores', 'Api\PrestadoresController@index');
Route::post('prestadores', 'Api\PrestadoresController@store');
Route::get('prestadores/{prestador}', 'Api\PrestadoresController@show');
Route::put('prestadores/{prestador}', 'Api\PrestadoresController@update');
Route::delete('prestadores/{prestador}', 'Api\PrestadoresController@destroy');
Route::get('prestadores/{prestador}/meuspacientes', 'Api\PrestadoresController@meuspacientes'); // Custon
