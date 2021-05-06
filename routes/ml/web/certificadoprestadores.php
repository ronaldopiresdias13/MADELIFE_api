<?php


use Illuminate\Support\Facades\Route;

Route::get('certificadoprestadores', 'Api\CertificadoprestadoresController@index');
Route::post('certificadoprestadores', 'Api\CertificadoprestadoresController@store');
Route::get('certificadoprestadores/{certificadoprestador}', 'Api\CertificadoprestadoresController@show');
Route::put('certificadoprestadores/{certificadoprestador}', 'Api\CertificadoprestadoresController@update');
Route::delete('certificadoprestadores/{certificadoprestador}', 'Api\CertificadoprestadoresController@destroy');
