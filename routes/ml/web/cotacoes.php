<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('compras')->group(function () {
            Route::get('cotacoes', 'Api\Web\Compras\CotacoesController@getAllByEmpresaId');
            Route::post('cotacoes', 'Api\Web\Compras\CotacoesController@store');
            Route::get('cotacoes/{cotacao}', 'Api\Web\Compras\CotacoesController@show');
            Route::put('cotacoes/{cotacao}', 'Api\Web\Compras\CotacoesController@update');
            Route::delete('cotacoes/{cotacao}', 'Api\Web\Compras\CotacoesController@destroy');
        });
    });
});
