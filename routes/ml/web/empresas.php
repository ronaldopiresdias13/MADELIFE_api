<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::get('empresas', 'Api\EmpresasController@index');
        Route::prefix('Empresas')->group(function () {
            Route::get('empresa', 'Web\Empresas\EmpresasController@show');
            Route::post('empresa', 'Web\Empresas\EmpresasController@store');
            Route::put('empresa/{empresa}', 'Web\Empresas\EmpresasController@update');
        });
    });
});

Route::get('empresas', 'Api\EmpresasController@index');
Route::post('empresas', 'Api\EmpresasController@store');
Route::get('empresas/{empresa}', 'Api\EmpresasController@show');
Route::put('empresas/{empresa}', 'Api\EmpresasController@update');
Route::delete('empresas/{empresa}', 'Api\EmpresasController@destroy');
