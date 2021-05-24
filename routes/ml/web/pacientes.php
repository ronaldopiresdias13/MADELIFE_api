<?php

use App\Http\Controllers\Web\Pacientes\PacientesController;
use Illuminate\Support\Facades\Route;

// Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('pacientes')->group(function () {
            Route::get('pacientesOfCliente', [PacientesController::class, 'pacientesOfCliente']);
        });
        Route::prefix('gestaoOrcamentaria')->group(function () {
            Route::get('pacientes/list', 'Api\Web\PacientesController@index');
            Route::get('pacientes/{paciente}', 'Api\Web\PacientesController@show');
            Route::post('pacientes', 'Api\Web\PacientesController@store');
            Route::put('pacientes/{paciente}', 'Api\Web\PacientesController@update');
            Route::delete('pacientes/{paciente}', 'Api\Web\PacientesController@destroy');
        });
        Route::get('pacientes/listNomePacientes', 'Api\Web\PacientesController@listNomePacientes');
    });
// });


Route::get('pacientes', 'Api\PacientesController@index');
Route::post('pacientes', 'Api\PacientesController@store');
Route::get('pacientes/{paciente}', 'Api\PacientesController@show');
Route::put('pacientes/{paciente}', 'Api\PacientesController@update');
Route::delete('pacientes/{paciente}', 'Api\PacientesController@destroy');
