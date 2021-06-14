<?php

use Illuminate\Support\Facades\Route;

Route::get('horariostrabalho', 'Api\HorariostrabalhoController@index');
Route::post('horariostrabalho', 'Api\HorariostrabalhoController@store');
Route::get('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@show');
Route::put('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@update');
Route::delete('horariostrabalho/{horariostrabalho}', 'Api\HorariostrabalhoController@destroy');
