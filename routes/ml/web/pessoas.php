<?php

use Illuminate\Support\Facades\Route;

Route::get('pessoas', 'Api\PessoasController@index');
Route::post('pessoas', 'Api\PessoasController@store');
Route::get('pessoas/{pessoa}', 'Api\PessoasController@show');
Route::put('pessoas/{pessoa}', 'Api\PessoasController@update');
Route::delete('pessoas/{pessoa}', 'Api\PessoasController@destroy');

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
        Route::post('pessoas/{pessoa}/adicionarTelefone', 'Web\Pessoas\PessoasController@adicionarTelefone');
        Route::get('pessoas/listaPessoaPorTipo', 'Web\Pessoas\PessoasController@listaPessoaPorTipo');
    });
});
