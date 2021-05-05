<?php

use App\Http\Controllers\Web\Prestadores\PrestadoresController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('areaClinica')->group(function () {
            Route::get('profissionais/historicopacientesprestador/{prestador}', 'Api\Web\PrestadoresController@historicopacientesprestador');
        });
        Route::prefix('prestadores')->group(function () {
            Route::get('recrutamento', [PrestadoresPrestadoresController::class, 'listRecrutamento']);
            Route::get('empresaPrestador/listaPrestadoresPorEmpresaIdEStatus', [ApiEmpresaPrestadorController::class, 'listaPrestadoresPorEmpresaIdEStatus']);
            Route::get('buscaprestadorexterno/{prestador}', [PrestadoresPrestadoresController::class, 'buscaprestadorexterno']);
        });
    });
});
