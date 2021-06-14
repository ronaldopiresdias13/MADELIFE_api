<?php

use Illuminate\Support\Facades\Route;

Route::get('pagamentos', 'Api\PagamentosController@index');
Route::post('pagamentos', 'Api\PagamentosController@store');
Route::get('pagamentos/{pagamento}', 'Api\PagamentosController@show');
Route::put('pagamentos/{pagamento}', 'Api\PagamentosController@update');
Route::delete('pagamentos/{pagamento}', 'Api\PagamentosController@destroy');
Route::get('pagamentosfiltro', 'Api\PagamentosController@filtro');                    // Custon
