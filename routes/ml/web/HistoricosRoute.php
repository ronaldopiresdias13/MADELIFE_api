<?php

use App\Http\Controllers\Web\Historicos\HistoricosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('historicos/escalas', [HistoricosController::class, 'historicoescalaporpacienteid']);
});
