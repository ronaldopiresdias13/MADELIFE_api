<?php

use Illuminate\Support\Facades\Route;

/*-------------- Auth Web --------------*/
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Auth\AuthController@login')->name('login');
    Route::post('register', 'Auth\AuthController@register');
    Route::post('reset', 'Auth\AuthController@reset');
    Route::post('change', 'Auth\AuthController@change');

    /*------------- Rotas Utilizando Token -------------*/
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'Auth\AuthController@logout');
        Route::get('user', 'Auth\AuthController@user');
    });
});

// Route::group([
//     'prefix' => 'app/v3_0_20/auth'
// ], function () {
//     Route::post('login', 'Api\App\v3_0_20\Auth\AuthController@login');
//     Route::post('register', 'Api\App\v3_0_20\Auth\AuthController@register');
//     Route::post('reset', 'Api\App\v3_0_20\Auth\AuthController@reset');

//     /* ------------- Rotas Utilizando Token -------------*/
//     Route::group(['middleware' => 'auth:api'], function () {
//         Route::post('change', 'Api\App\v3_0_20\Auth\AuthController@change');
//         Route::get('logout', 'Api\App\v3_0_20\Auth\AuthController@logout');
//         // Route::get('user', 'Auth\AuthController@user');
//     });
// });
Route::group([
    'prefix' => 'app/v3_0_21/auth'
], function () {
    Route::post('login', 'Api\App\v3_0_21\Auth\AuthController@login');
    Route::post('register', 'Api\App\v3_0_21\Auth\AuthController@register');
    Route::post('reset', 'Api\App\v3_0_21\Auth\AuthController@reset');

    /* ------------- Rotas Utilizando Token -------------*/
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('change', 'Api\App\v3_0_21\Auth\AuthController@change');
        Route::get('logout', 'Api\App\v3_0_21\Auth\AuthController@logout');
        // Route::get('user', 'Auth\AuthController@user');
    });
});
Route::group([
    'prefix' => 'app/v3_0_22/auth'
], function () {
    Route::post('login', 'Api\App\v3_0_22\Auth\AuthController@login');
    Route::post('register', 'Api\App\v3_0_22\Auth\AuthController@register');
    Route::post('reset', 'Api\App\v3_0_22\Auth\AuthController@reset');

    /* ------------- Rotas Utilizando Token -------------*/
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('change', 'Api\App\v3_0_22\Auth\AuthController@change');
        Route::get('logout', 'Api\App\v3_0_22\Auth\AuthController@logout');
        // Route::get('user', 'Auth\AuthController@user');
    });
});
