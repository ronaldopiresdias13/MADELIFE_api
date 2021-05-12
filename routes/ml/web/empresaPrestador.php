<?php

use Illuminate\Support\Facades\Route;

Route::get('empresaPrestador', 'Api\EmpresaPrestadorController@index');
Route::post('empresaPrestador', 'Api\EmpresaPrestadorController@store');
Route::get('empresaPrestador/{empresaPrestador}', 'Api\EmpresaPrestadorController@show');
Route::put('empresaPrestador/{empresaPrestador}', 'Api\EmpresaPrestadorController@update');
Route::delete('empresaPrestador/{empresaPrestador}', 'Api\EmpresaPrestadorController@destroy');
Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\EmpresaPrestadorController@downloadFile');
Route::get('empresaPrestador/empresa/{empresa}', 'Api\EmpresaPrestadorController@indexbyempresa');
Route::get('empresaPrestador/count/{empresa}', 'Api\EmpresaPrestadorController@quantidadeempresaprestador');
