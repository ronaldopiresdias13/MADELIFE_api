<?php

use App\Http\Controllers\Api\Web\Compras\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('compras')->group(function () {
            Route::get('produtos/getAllProdutosByIdEmpresa', [ProdutoController::class, 'getAllProdutosByIdEmpresa']);
        });
    });
});
