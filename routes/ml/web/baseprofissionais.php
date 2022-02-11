<?php

use App\Http\Controllers\Web\BaseProfissionais\BaseProfissionaisController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::get('baseprofissionais', [BaseProfissionaisController::class, 'index']);
        Route::post('baseprofissionais', [BaseProfissionaisController::class, 'store']);
        Route::get('baseprofissionais/{baseprofissionais}', [BaseProfissionaisController::class, 'show']);
        Route::put('baseprofissionais/{baseprofissionais}', [BaseProfissionaisController::class, 'update']);
        Route::delete('baseprofissionais/{baseprofissionais}', [BaseProfissionaisController::class, 'destroy']);
    });
});
