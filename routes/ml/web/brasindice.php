<?php

use App\Http\Controllers\Web\Brasindice\BrasindiceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::prefix('brasindice')->group(function () {
            Route::get('brasindice', [BrasindiceController::class, 'index']);
            Route::post('brasindice', [BrasindiceController::class, 'store']);
            Route::get('brasindice/{brasindice}', [BrasindiceController::class, 'show']);
            Route::put('brasindice/{brasindice}', [BrasindiceController::class, 'update']);
            Route::delete('brasindice/{brasindice}', [BrasindiceController::class, 'destroy']);
        });
    });
});
