<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('gestaoOrcamentaria')->group(function () {
            Route::get('responsaveis/list', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@index');
            Route::get('responsaveis/{responsavel}', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@show');
            Route::post('responsaveis', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@store');
            Route::put('responsaveis/{responsavel}', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@update');
            Route::delete('responsaveis/{responsavel}', 'Api\Web\GestaoOrcamentaria\ResponsaveisController@destroy');
        });
    });
});

Route::get('responsaveis', 'Api\ResponsaveisController@index');
Route::post('responsaveis', 'Api\ResponsaveisController@store');
Route::get('responsaveis/{responsavel}', 'Api\ResponsaveisController@show');
Route::put('responsaveis/{responsavel}', 'Api\ResponsaveisController@update');
Route::delete('responsaveis/{responsavel}', 'Api\ResponsaveisController@destroy');
