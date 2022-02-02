<?php

use App\Http\Controllers\Web\Produtos\ProdutosController;
use App\Http\Controllers\Api\Web\Compras\ProdutoController as ProdutoController2;
use App\Http\Controllers\Api_V2_0\ProductCompany\ProductCompanyController;
use App\Http\Controllers\Api_V2_0\Products\ProductsController;
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
    // Endpoint da Controller ProductCompany
    Route::get('productsToCompany', [ProductCompanyController::class, 'index']);
    Route::post('productsToCompany', [ProductCompanyController::class, 'store']);
    Route::delete('productsToCompany/{productCompany}', [ProductCompanyController::class, 'destroy']);
    Route::get('productsCompanyById', [ProductCompanyController::class, 'ProductsCompanyById']);
    // Endpoint da Controller Product com Autenticação
    Route::get('products', [ProductsController::class, 'index']);
    Route::get('products/filter', [ProductsController::class, 'ProductsFilter']);
    Route::post('product', [ProductsController::class, 'store']);
    Route::put('product/{product}', [ProductsController::class, 'update']);
    // Route::delete('product/{product}', [ProductsController::class, 'destroy']);
});

Route::get('produtos', 'Api\ProdutosController@index');
Route::post('produtos', 'Api\ProdutosController@store');
Route::get('produtos/{produto}', 'Api\ProdutosController@show');
Route::put('produtos/{produto}', 'Api\ProdutosController@update');
Route::delete('produtos/{produto}', 'Api\ProdutosController@destroy');


// Novos endpoints para produtos

