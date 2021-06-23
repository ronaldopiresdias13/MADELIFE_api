<?php

use App\Http\Controllers\Web\Unidadesfederativas\UnidadesfederativasController;
use App\Models\Unidadefederativa;
use Illuminate\Support\Facades\Route;

// Route::group(['middleware' => 'auth:api'], function () {
    Route::get('web/uf/list', [UnidadesfederativasController::class, 'index']);
// });
