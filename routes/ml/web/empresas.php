<?php

use Illuminate\Support\Facades\Route;

Route::get('empresas', 'Api\EmpresasController@index');
Route::post('empresas', 'Api\EmpresasController@store');
Route::get('empresas/{empresa}', 'Api\EmpresasController@show');
Route::put('empresas/{empresa}', 'Api\EmpresasController@update');
Route::delete('empresas/{empresa}', 'Api\EmpresasController@destroy');
