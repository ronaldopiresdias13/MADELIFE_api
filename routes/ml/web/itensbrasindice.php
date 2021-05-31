<?php

use App\Http\Controllers\Web\ItensBrasindice\ItensBrasindiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('web')->group(function () {
        Route::prefix('itensBrasindice')->group(function () {
            Route::get('itensbrasindice', [ItensBrasindiceController::class, 'index']);
            Route::post('itensbrasindice', [ItensBrasindiceController::class, 'store']);
            Route::get('itensbrasindice/{itensbrasindice}', [ItensBrasindiceController::class, 'show']);
            Route::put('itensbrasindice/{itensbrasindice}', [ItensBrasindiceController::class, 'update']);
            Route::delete('itensbrasindice/{itensbrasindice}', [ItensBrasindiceController::class, 'destroy']);
        });
    });
