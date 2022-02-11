<?php

use App\Http\Controllers\Api_V2_0\ML_Category\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('categories', [CategoryController::class, 'index']);