<?php

use App\Http\Controllers\Web\Tutorials\TutorialsController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::get('tutorials',                  [TutorialsController::class, 'index']);
        Route::post('tutorials',                 [TutorialsController::class, 'store']);
        Route::get('tutorials/{tutorial}',    [TutorialsController::class, 'show']);
        Route::put('tutorials/{tutorial}',    [TutorialsController::class, 'update']);
        Route::delete('tutorials/{tutorial}', [TutorialsController::class, 'destroy']);
        Route::delete('tutorials/file/{tutorialFile}', [TutorialsController::class, 'destroyFile']);
    });
});
