<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('gestaoOrcamentaria')->group(function () {
            Route::get('servicos/list', 'Api\Web\GestaoOrcamentaria\ServicosController@index');
            Route::get('servicos/listServicosFormacoes', 'Api\Web\GestaoOrcamentaria\ServicosController@listServicosFormacoes');
            Route::post('servicos', 'Api\Web\GestaoOrcamentaria\ServicosController@store');
            Route::get('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@show');
            Route::put('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@update');
            Route::delete('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@destroy');
        });
    });
});

Route::get('servicos', 'Api\ServicosController@index');
Route::post('servicos', 'Api\ServicosController@store');
Route::get('servicos/{servico}', 'Api\ServicosController@show');
Route::put('servicos/{servico}', 'Api\ServicosController@update');
Route::delete('servicos/{servico}', 'Api\ServicosController@destroy');
Route::get('servicos/empresa/{empresa}', 'Api\ServicosController@indexbyempresa');
