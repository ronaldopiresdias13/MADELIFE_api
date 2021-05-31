<?php


use Illuminate\Support\Facades\Route;


Route::get('cuidadoPacientes', 'Api\CuidadoPacienteController@index');
Route::post('cuidadoPacientes', 'Api\CuidadoPacienteController@store');
Route::get('cuidadoPacientes/{cuidadoPaciente}', 'Api\CuidadoPacienteController@show');
Route::put('cuidadoPacientes/{cuidadoPaciente}', 'Api\CuidadoPacienteController@update');
Route::delete('cuidadoPacientes/{cuidadoPaciente}', 'Api\CuidadoPacienteController@destroy');
Route::get('cuidadoPacientes/paciente/{paciente}', 'Api\CuidadoPacienteController@buscacuidadosdopaciente');
Route::get('cuidadoPacientes/groupby/{paciente}', 'Api\CuidadoPacienteController@groupbycuidadosdopaciente');
