<?php

use Illuminate\Support\Facades\Route;

Route::get('userAcessos', 'Api\UserAcessoController@index');
Route::post('userAcessos', 'Api\UserAcessoController@store');
Route::get('userAcessos/{userAcesso}', 'Api\UserAcessoController@show');
Route::put('userAcessos/{userAcesso}', 'Api\UserAcessoController@update');
Route::delete('userAcessos/{userAcesso}', 'Api\UserAcessoController@destroy');
