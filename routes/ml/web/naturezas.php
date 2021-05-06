<?php

use App\Http\Controllers\Web\Orcs\OrcsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('financeiro')->group(function () {
            Route::get('naturezas', 'Api\Web\Financeiro\NaturezasController@index');
            Route::post('naturezas', 'Api\Web\Financeiro\NaturezasController@store');
            Route::get('naturezas/{natureza}', 'Api\Web\Financeiro\NaturezasController@show');
            Route::put('naturezas/{natureza}', 'Api\Web\Financeiro\NaturezasController@update');
            Route::delete('naturezas/{natureza}', 'Api\Web\Financeiro\NaturezasController@destroy');
        });
    });
});
