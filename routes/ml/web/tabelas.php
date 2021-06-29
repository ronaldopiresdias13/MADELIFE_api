<?php

use App\Http\Controllers\Web\Tabelas\TabelasController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('web/tabelas/list', [TabelasController::class, 'index']);
});