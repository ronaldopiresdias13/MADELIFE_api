<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('areaClinica')->group(function () {
            Route::get('transcricoes', 'Api\Web\AreaClinica\TranscricoesController@index');
            Route::post('transcricoes', 'Api\Web\AreaClinica\TranscricoesController@store');
            Route::get('transcricoes/{transcricao}', 'Api\Web\AreaClinica\TranscricoesController@show');
            Route::put('transcricoes/{transcricao}', 'Api\Web\AreaClinica\TranscricoesController@update');
            Route::delete('transcricoes/{transcricao}', 'Api\Web\AreaClinica\TranscricoesController@destroy');
            Route::get('transcricoes/count/{empresa}', 'Api\Web\AreaClinica\TranscricoesController@quantidadetranscricoes');
        });
    });
    Route::get('transcricoes/listaTranscricoes', 'Api\Web\AreaClinica\TranscricoesController@listaTranscricoes');
});
