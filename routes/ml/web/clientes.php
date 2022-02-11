<?php

use App\Http\Controllers\Api\Web\GestaoOrcamentaria\ClientesController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('gestaoOrcamentaria')->group(function () {
            Route::get('clientes/list', 'Api\Web\GestaoOrcamentaria\ClientesController@index');
            Route::get('clientes/{cliente}', 'Api\Web\GestaoOrcamentaria\ClientesController@show');
            Route::post('clientes', 'Api\Web\GestaoOrcamentaria\ClientesController@store');
            Route::put('clientes/{cliente}', 'Api\Web\GestaoOrcamentaria\ClientesController@update');
            Route::delete('clientes/{cliente}', 'Api\Web\GestaoOrcamentaria\ClientesController@destroy');
            Route::get('clientes', [ClientesController::class, 'clientePage']);
        });
        Route::get('clientes/list', 'Web\Clientes\ClientesController@index');
        Route::get('clientes/{cliente}', 'Web\Clientes\ClientesController@show');
        Route::post('clientes', 'Web\Clientes\ClientesController@store');
        Route::put('clientes/{cliente}', 'Web\Clientes\ClientesController@update');
        Route::delete('clientes/{cliente}', 'Web\Clientes\ClientesController@destroy');
    });
});
