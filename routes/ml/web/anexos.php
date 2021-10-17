<?php

use App\Http\Controllers\Web\Anexos\AnexosController;
use Illuminate\Support\Facades\Route;


Route::prefix('web')->group(function () {
    Route::post('anexos', [AnexosController::class, 'store']);
    Route::get('anexos/download/{anexo}', [AnexosController::class, 'download']);
});
