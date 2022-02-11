<?php

use App\Http\Controllers\Web\Despesas\DespesasController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('web/despesas/list', [DespesasController::class, 'index']);
});