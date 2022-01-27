<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::delete('profissionalConvenio/{profissionalConvenio}', 'Web\ProfissionalConvenio\ProfissionalConvenioController@destroy');
    });
});
