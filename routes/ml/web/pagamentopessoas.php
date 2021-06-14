<?php

use App\Http\Controllers\Api\Web\Financeiro\PagamentosCnabController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('financeiro')->group(function () {
            Route::get('listPagamentosByEmpresaId', 'Api\Web\Financeiro\PagamentopessoasController@listPagamentosByEmpresaId');
            Route::get('pagamentos/pessoas', [PagamentosCnabController::class, 'listPagamentosByEmpresaId']);
            Route::get('pagamentos/cnab/groupByPagamentoByMesAndEmpresaId', [PagamentosCnabController::class, 'groupByPagamentoByMesAndEmpresaId']);
            Route::post('atualizarSituacaoPagamentoDiretoria', [PagamentosCnabController::class, 'atualizarSituacaoPagamentoDiretoria']);
        });
        Route::prefix('departamentoPessoal')->group(function () {
            Route::get('buscarPagamentosPessoaPorPeriodoEmpresaId', 'Api\Web\DepartamentoPessoal\PagamentopessoasController@buscarPagamentosPessoaPorPeriodoEmpresaId');
        });
    });
});


Route::get('pagamentopessoas', 'Api\PagamentopessoasController@index');
Route::post('pagamentopessoas', 'Api\PagamentopessoasController@store');
Route::get('pagamentopessoas/{pagamentopessoa}', 'Api\PagamentopessoasController@show');
Route::put('pagamentopessoas/{pagamentopessoa}', 'Api\PagamentopessoasController@update');
Route::delete('pagamentopessoas/{pagamentopessoa}', 'Api\PagamentopessoasController@destroy');
