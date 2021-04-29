<?php

use App\Http\Controllers\Web\Produtos\ProdutosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('produtos')->group(function () {
            Route::get('localizacaoOfProdutos', [ProdutosController::class, 'buscaLocalizacaoProdutos']);
            Route::get('buscaProdutoPorCodBarra', [ProdutosController::class, 'buscaProdutoPorCodBarra']);
        });
    });
});
