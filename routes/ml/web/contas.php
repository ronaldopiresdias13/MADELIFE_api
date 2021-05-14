<?php


use Illuminate\Support\Facades\Route;

Route::get('contas', 'Api\ContasController@index');
Route::post('contas', 'Api\ContasController@store');
Route::get('contas/{contas}', 'Api\ContasController@show');
Route::put('contas/{contas}', 'Api\ContasController@update');
Route::delete('contas/{contas}', 'Api\ContasController@destroy');
