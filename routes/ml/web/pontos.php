<?php

use App\Http\Controllers\Web\Pontos\PontosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('pontos')->group(function () {
            Route::get('pontosPrestadores', [PontosController::class, 'pontosPrestadores']);
            Route::put('correcaoPontos', [PontosController::class, 'correcaoPontos']);
        });
        Route::prefix('departamentoPessoal')->group(function () {
            Route::get('pontos/pontosPrestadores', 'Api\Web\DepartamentoPessoal\PontosController@pontosPrestadores');
        });
        Route::post('pontos/checkin/{escala}', 'Api\PontosController@checkin'); // Custon
        Route::post('pontos/checkout/{escala}', 'Api\PontosController@checkout'); // Custon
    });
});
