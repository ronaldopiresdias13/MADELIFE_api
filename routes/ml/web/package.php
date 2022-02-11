<?php

use App\Http\Controllers\Api_V2_0\ML_Package\PackageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
       Route::get('packages', [PackageController::class, 'index']);
       Route::post('package', [PackageController::class, 'store']);
       Route::put('package/{package}', [PackageController::class, 'update']);
       Route::get('package/{package}', [PackageController::class, 'show']);
    });
});
