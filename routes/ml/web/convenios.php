<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('recursosHumanos')->group(function () {
            Route::get('convenios', 'Api\Web\RecursosHumanos\ConveniosController@index');
            Route::post('convenios', 'Api\Web\RecursosHumanos\ConveniosController@store');
            Route::get('convenios/{convenio}', 'Api\Web\RecursosHumanos\ConveniosController@show');
            Route::put('convenios/{convenio}', 'Api\Web\RecursosHumanos\ConveniosController@update');
            Route::delete('convenios/{convenio}', 'Api\Web\RecursosHumanos\ConveniosController@destroy');
        });
    });
});
