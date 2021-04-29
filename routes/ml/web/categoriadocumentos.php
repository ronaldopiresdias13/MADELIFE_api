<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('convenio')->group(function () {
            Route::get('categoriadocumentos/listCategorias', 'Api\Web\Convenio\CategoriadocumentosController@listCategorias');
        });
        Route::prefix('areaClinica')->group(function () {
            Route::get('categoriadocumentos/listCategorias', 'Api\Web\AreaClinica\CategoriadocumentosController@listCategorias');
            Route::post('categoriadocumentos/newCategoria', 'Api\Web\AreaClinica\CategoriadocumentosController@newCategoria');
            Route::put('categoriadocumentos/update/{categoriadocumento}', 'Api\Web\AreaClinica\CategoriadocumentosController@update');
            Route::delete('categoriadocumentos/delete/{categoriadocumento}', 'Api\Web\AreaClinica\CategoriadocumentosController@delete');
        });
    });
});
