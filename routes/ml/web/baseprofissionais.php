<?php

use App\Http\Controllers\Web\BaseProfissionais\BaseProfissionaisController;
use Illuminate\Support\Facades\Route;

// Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
            Route::get('baseprofissionais', [BaseProfissionaisController::class,'index']);
            Route::post('baseprofissionais', [BaseProfissionaisController::class,'store']);
            Route::get('baseprofissionais/{profissional}', [BaseProfissionaisController::class,'show']);
            Route::put('baseprofissionais/{profissional}', [BaseProfissionaisController::class,'update']);
            Route::delete('baseprofissionais/{profissional}', [BaseProfissionaisController::class,'destroy']);
    });
// });