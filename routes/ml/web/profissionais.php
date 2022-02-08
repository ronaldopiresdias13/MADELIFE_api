<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('recursosHumanos')->group(function () {
            Route::post('profissionais/novoProfissional', 'Api\Web\RecursosHumanos\ProfissionaisController@novoProfissional');
            Route::get('profissionais', 'Api\Web\RecursosHumanos\ProfissionaisController@index');
            Route::get('profissionais/{profissional}', 'Api\Web\RecursosHumanos\ProfissionaisController@show');
            Route::put('profissionais/{profissional}', 'Api\Web\RecursosHumanos\ProfissionaisController@update');
            Route::delete('profissionais/{profissional}', 'Api\Web\RecursosHumanos\ProfissionaisController@destroy');
            // Route::get('profissionais/downloadfile/{anexo}', 'Api\Web\RecursosHumanos\ProfissionaisController@downloadfile');
            Route::get('profissionaisAcessos', 'Api\Web\RecursosHumanos\ProfissionaisController@profissionaisPage');
        });
    });
});
