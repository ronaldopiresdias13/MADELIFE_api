<?php

use App\Http\Controllers\Web\Formacoes\FormacoesController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('formacoes')->group(function () {
            Route::get('', [FormacoesController::class, 'index']);
        });
    });
});

Route::post('formacoes', 'Api\FormacoesController@store');
Route::get('formacoes/{formacao}', 'Api\FormacoesController@show');
Route::put('formacoes/{formacao}', 'Api\FormacoesController@update');
Route::delete('formacoes/{formacao}', 'Api\FormacoesController@destroy');
