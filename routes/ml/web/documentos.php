<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {

    /*----------------- Web -----------------*/
    Route::prefix('web')->group(function () {
        Route::prefix('areaClinica')->group(function () {
            Route::get('documentos/listDocumentos', 'Api\Web\AreaClinica\DocumentosController@listDocumentos');
            Route::delete('documentos/{documento}', 'Api\Web\AreaClinica\DocumentosController@destroy');
        });
    });
});
