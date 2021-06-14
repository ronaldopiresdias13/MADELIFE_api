<?php


use Illuminate\Support\Facades\Route;

Route::get('ordemservicoPrestadores', 'Api\OrdemservicoPrestadoresController@index');
Route::post('ordemservicoPrestadores', 'Api\OrdemservicoPrestadoresController@store');
Route::get('ordemservicoPrestadores/{ordemservicoPrestador}', 'Api\OrdemservicoPrestadoresController@show');
Route::put('ordemservicoPrestadores/{ordemservicoPrestador}', 'Api\OrdemservicoPrestadoresController@update');
Route::delete('ordemservicoPrestadores/{ordemservicoPrestador}', 'Api\OrdemservicoPrestadoresController@destroy');
Route::get('ordemservicoPrestadores/ordemservico/{ordemservico}', 'Api\OrdemservicoPrestadoresController@profissionaisatribuidosaopaciente');
