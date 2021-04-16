<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('chat')->group(function () {
            Route::get('get_conversas', 'Api\Web\Chat\ChatGeralController@get_conversas');
            Route::get('get_pessoas', 'Api\Web\Chat\ChatGeralController@get_pessoas');
            Route::post('enviarArquivos', 'Api\Web\Chat\ChatGeralController@enviarArquivos');
        });
    });
});
