<?php

use App\Http\Controllers\Web\Tiss\TissController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('tiss/gerarXml/{cliente}', [TissController::class, 'gerarXml']);
    Route::put('tiss/editarXml/{cliente}', [TissController::class, 'editarXml']);
    // Route::get('tiss/gerarXmlPorCliente/{cliente}', [TissController::class, 'gerarXmlPorCliente']);

    Route::get('tiss', [TissController::class, 'index']);
    Route::get('tiss/download/{tiss}', [TissController::class, 'downloadTiss']);
});
