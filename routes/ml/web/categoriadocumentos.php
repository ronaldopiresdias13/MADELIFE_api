<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {

    /*----------------- Web -----------------*/
    Route::prefix('web')->group(function () {
        Route::prefix('convenio')->group(function () {
            Route::get('categoriadocumentos/listCategorias', 'Api\Web\Convenio\CategoriadocumentosController@listCategorias');
        });
    });
});
