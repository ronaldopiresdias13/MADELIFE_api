<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {

    /*----------------- Web -----------------*/
    Route::prefix('web')->group(function () {
        Route::prefix('compras')->group(function () {
            Route::get('fornecedores', 'Api\Web\Compras\FornecedoresController@getAllByEmpresaId');
            Route::post('fornecedores', 'Api\Web\Compras\FornecedoresController@store');
            Route::get('fornecedores/{fornecedor}', 'Api\Web\Compras\FornecedoresController@show');
            Route::put('fornecedores/{fornecedor}', 'Api\Web\Compras\FornecedoresController@update');
            Route::delete('fornecedores/{fornecedor}', 'Api\Web\Compras\FornecedoresController@destroy');

            // Route::get('fornecedores', [FornecedoresController::class, 'getAllByEmpresaId']);
            // Route::post('fornecedores', [FornecedoresController::class, 'store']);
            // Route::get('fornecedores/{fornecedor}', [FornecedoresController::class, 'show']);
            // Route::put('fornecedores/{fornecedor}', [FornecedoresController::class, 'update']);
            // Route::delete('fornecedores/{fornecedor}', [FornecedoresController::class, 'destroy']);
        });
    });
});
