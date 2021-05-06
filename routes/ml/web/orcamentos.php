<?php

use App\Http\Controllers\Web\Orcs\OrcsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('gestaoOrcamentaria')->group(function () {
            Route::get('orcamentos/list/{empresa}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@index');
            Route::get('orcamentos/{orcamento}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@show');
            Route::post('orcamentos', 'Api\Web\GestaoOrcamentaria\OrcamentosController@store');
            Route::put('orcamentos/{orcamento}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@update');
            Route::delete('orcamentos/{orcamento}', 'Api\Web\GestaoOrcamentaria\OrcamentosController@destroy');
            Route::post('orcamentos/enviarOrcamentoPorEmail', 'Api\Web\GestaoOrcamentaria\OrcamentosController@enviarOrcamentoPorEmail');
        });
    });
});
