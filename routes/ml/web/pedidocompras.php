<?php

use Illuminate\Support\Facades\Route;

Route::get('pedidocompras', 'Api\PedidocomprasController@index');
Route::post('pedidocompras', 'Api\PedidocomprasController@store');
Route::get('pedidocompras/{pedidocompra}', 'Api\PedidocomprasController@show');
Route::put('pedidocompras/{pedidocompra}', 'Api\PedidocomprasController@update');
Route::delete('pedidocompras/{pedidocompra}', 'Api\PedidocomprasController@destroy');
