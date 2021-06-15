<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('Empresas')->group(function () {
            Route::get('empresa', 'Web\Empresas\EmpresasController@show');
        });
    });
});

Route::get('empresas', 'Api\EmpresasController@index');
Route::post('empresas', 'Api\EmpresasController@store');
Route::get('empresas/{empresa}', 'Api\EmpresasController@show');
Route::put('empresas/{empresa}', 'Api\EmpresasController@update');
Route::delete('empresas/{empresa}', 'Api\EmpresasController@destroy');
