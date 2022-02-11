<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::post('empresaPrestador', 'Web\EmpresaPrestador\EmpresaPrestadorController@store');
        Route::put('empresaPrestador/{empresaPrestador}', 'Web\EmpresaPrestador\EmpresaPrestadorController@update');
        Route::get('empresaPrestador/anexos/{empresaPrestador}', 'Web\EmpresaPrestador\EmpresaPrestadorController@anexos');
    });
});

Route::put('empresaPrestador/desativar/{empresaPrestador}', 'Web\EmpresaPrestador\EmpresaPrestadorController@desativarPrestador');
Route::put('empresaPrestador/ativar/{empresaPrestador}', 'Web\EmpresaPrestador\EmpresaPrestadorController@ativarPrestador');
Route::get('empresaPrestador', 'Api\EmpresaPrestadorController@index');
Route::get('empresaPrestador/{empresaPrestador}', 'Api\EmpresaPrestadorController@show');
Route::delete('empresaPrestador/{empresaPrestador}', 'Api\EmpresaPrestadorController@destroy');
Route::get('empresaPrestador/{empresaPrestador}/downloadFile', 'Api\EmpresaPrestadorController@downloadFile');
Route::get('empresaPrestador/empresa/{empresa}', 'Api\EmpresaPrestadorController@indexbyempresa');
Route::get('empresaPrestador/count/{empresa}', 'Api\EmpresaPrestadorController@quantidadeempresaprestador');
