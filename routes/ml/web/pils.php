<?php

use Illuminate\Support\Facades\Route;

Route::get('pils', 'Api\PilsController@index');
Route::post('pils', 'Api\PilsController@store');
Route::get('pils/{pil}', 'Api\PilsController@show');
Route::put('pils/{pil}', 'Api\PilsController@update');
Route::delete('pils/{pil}', 'Api\PilsController@destroy');
