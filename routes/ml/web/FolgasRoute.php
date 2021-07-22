<?php

use App\Http\Controllers\Web\Folgas\FolgasController;
use Illuminate\Support\Facades\Route;

// Route::group(['middleware' => 'auth:api'], function () {
    Route::get('web/folgas', [FolgasController::class, 'index']);
    Route::post('web/folgas/adicionarFolga', [FolgasController::class, 'adicionarFolga']);
    Route::put('web/folgas/adicionarSubstituto/{folga}', [FolgasController::class, 'adicionarSubstituto']);
    // Route::get('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@show');
    // Route::put('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@update');
    // Route::delete('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@destroy');
// });
