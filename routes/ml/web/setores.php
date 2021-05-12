<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('recursosHumanos')->group(function () {
            Route::get('setores', 'Api\Web\RecursosHumanos\SetoresController@index');
            Route::post('setores', 'Api\Web\RecursosHumanos\SetoresController@store');
            Route::get('setores/{setor}', 'Api\Web\RecursosHumanos\SetoresController@show');
            Route::put('setores/{setor}', 'Api\Web\RecursosHumanos\SetoresController@update');
            Route::delete('setores/{setor}', 'Api\Web\RecursosHumanos\SetoresController@destroy');
        });
    });
});
