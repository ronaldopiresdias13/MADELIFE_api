<?php

use App\Http\Controllers\Web\Tiss\TissController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('tiss/gerarXml/{cliente}', [TissController::class, 'gerarXml']);
    Route::get('tiss/gerarXmlPorCliente/{cliente}', [TissController::class, 'gerarXmlPorCliente']);
});
