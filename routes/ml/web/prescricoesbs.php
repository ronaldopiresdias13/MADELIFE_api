<?php


use Illuminate\Support\Facades\Route;

Route::get('prescricoesbs', 'Api\PrescricoesbsController@index');
Route::post('prescricoesbs', 'Api\PrescricoesbsController@store');
Route::get('prescricoesbs/{prescricaob}', 'Api\PrescricoesbsController@show');
Route::put('prescricoesbs/{prescricaob}', 'Api\PrescricoesbsController@update');
Route::delete('prescricoesbs/{prescricaob}', 'Api\PrescricoesbsController@destroy');
