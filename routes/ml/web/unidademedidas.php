<?php

use Illuminate\Support\Facades\Route;

Route::get('unidademedidas', 'Api\UnidademedidasController@index');
Route::post('unidademedidas', 'Api\UnidademedidasController@store');
Route::get('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@show');
Route::put('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@update');
Route::delete('unidademedidas/{unidademedida}', 'Api\UnidademedidasController@destroy');
