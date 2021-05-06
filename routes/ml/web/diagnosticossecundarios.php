<?php

use Illuminate\Support\Facades\Route;
Route::get('diagnosticossecundarios', 'Api\DiagnosticossecundariosController@index');
Route::post('diagnosticossecundarios', 'Api\DiagnosticossecundariosController@store');
Route::get('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@show');
Route::put('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@update');
Route::delete('diagnosticossecundarios/{diagnosticossecundario}', 'Api\DiagnosticossecundariosController@destroy');

