<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('financeiro')->group(function () {
            Route::get('categorianaturezas', 'Api\Web\Financeiro\CategorianaturezasController@index');
            Route::post('categorianaturezas', 'Api\Web\Financeiro\CategorianaturezasController@store');
            Route::get('categorianaturezas/{categorianatureza}', 'Api\Web\Financeiro\CategorianaturezasController@show');
            Route::put('categorianaturezas/{categorianatureza}', 'Api\Web\Financeiro\CategorianaturezasController@update');
            Route::delete('categorianaturezas/{categorianatureza}', 'Api\Web\Financeiro\CategorianaturezasController@destroy');
        });
    });
});
