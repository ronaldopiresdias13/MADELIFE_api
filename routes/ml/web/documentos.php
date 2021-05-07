<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('areaClinica')->group(function () {
            Route::get('documentos/listDocumentos', 'Api\Web\AreaClinica\DocumentosController@listDocumentos');
            Route::delete('documentos/{documento}', 'Api\Web\AreaClinica\DocumentosController@destroy');
        });
        Route::get('documentos/listDocumentosByEmpresa', 'Api\Web\DocumentosController@listDocumentosByEmpresa');
        Route::get('documentos/listDocumentosByConvenio', 'Api\Web\DocumentosController@listDocumentosByConvenio');
        Route::get('documentos/listDocumentosByResponsavel', 'Api\Web\DocumentosController@listDocumentosByResponsavel');
        Route::get('documentos/listDocumentos', 'Api\Web\DocumentosController@listDocumentos');
        Route::post('documentos/newDocumento', 'Api\Web\DocumentosController@newDocumento');
        Route::get('documentos/download/{documento}', 'Api\Web\DocumentosController@download');
        Route::delete('documentos/delete/{documento}', 'Api\Web\DocumentosController@delete');
    });
});
