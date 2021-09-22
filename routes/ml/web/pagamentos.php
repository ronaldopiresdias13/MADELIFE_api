<?php

use App\Http\Controllers\Api\PagamentosController;
use App\Http\Controllers\Web\Pagamentos\PagamentosController as PagamentosPagamentosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('pagamentos/filtroPagamentosFinanceiro', [PagamentosController::class, 'filtroPagamentoFinanceiro']);
    Route::get('pagamentos/filtro', [PagamentosPagamentosController::class, 'filtroFluxoDeCaixa']);
});

Route::get('pagamentos', 'Api\PagamentosController@index');
Route::post('pagamentos', 'Api\PagamentosController@store');
Route::get('pagamentos/{pagamento}', 'Api\PagamentosController@show');
Route::put('pagamentos/{pagamento}', 'Api\PagamentosController@update');
Route::delete('pagamentos/{pagamento}', 'Api\PagamentosController@destroy');
Route::get('pagamentosfiltro', 'Api\PagamentosController@filtro');

// Custon
