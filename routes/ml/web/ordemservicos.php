<?php

use App\Http\Controllers\Web\Ordemservicos\OrdemservicosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('ordemservicos')->group(function () {
            Route::get('', [OrdemservicosController::class, 'index']);
        });
        Route::get('ordemservicos/listaOrdemServicosEscalas', 'Api\Web\GestaoOrcamentaria\OrdemservicosController@listaOrdemServicosEscalas');
    });
    Route::get('ordemservicos/listaOrdemServicosEscalas', 'Api\OrdemservicosController@listaOrdemServicosEscalas');
});

Route::get('ordemservicos', 'Api\OrdemservicosController@index');
Route::post('ordemservicos', 'Api\OrdemservicosController@store');
Route::get('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@show');
Route::put('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@update');
Route::delete('ordemservicos/{ordemservico}', 'Api\OrdemservicosController@destroy');
Route::get(
    'ordemservicos/{ordemservico}/horariomedicamentos',
    'Api\OrdemservicosController@horariomedicamentos'
); // Custon
Route::get('ordemservicos/count/{empresa}', 'Api\OrdemservicosController@quantidadeordemservicos');
Route::get('ordemservicos/groupbyservico/{empresa}', 'Api\OrdemservicosController@groupbyservicos');

