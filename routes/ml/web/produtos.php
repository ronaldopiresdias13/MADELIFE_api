<?php

use App\Http\Controllers\Web\Produtos\ProdutosController;
use App\Http\Controllers\Api\Web\Compras\ProdutoController as ProdutoController2;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('produtos')->group(function () {
            Route::get('localizacaoOfProdutos', [ProdutosController::class, 'buscaLocalizacaoProdutos']);
            Route::get('buscaProdutoPorCodBarra', [ProdutosController::class, 'buscaProdutoPorCodBarra']);
            Route::post('salvaProdutosImportados', [ProdutosController::class, 'salvaProdutosImportados']);
        });
        Route::prefix('compras')->group(function () {
            Route::get('produtos', [ProdutosController::class, 'pegarProdutosPorId']);
            Route::get('produtos/getAllProdutosByIdEmpresa', [ProdutoController2::class, 'getAllProdutosByIdEmpresa']);
        });
    });
});

Route::get('produtos', 'Api\ProdutosController@index');
Route::post('produtos', 'Api\ProdutosController@store');
Route::get('produtos/{produto}', 'Api\ProdutosController@show');
Route::put('produtos/{produto}', 'Api\ProdutosController@update');
Route::delete('produtos/{produto}', 'Api\ProdutosController@destroy');
