<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('contasbancarias', 'Api\ContasbancariasController@index');
    Route::post('contasbancarias', 'Api\ContasbancariasController@store');
    Route::get('contasbancarias/{contasbancaria}', 'Api\ContasbancariasController@show');
    Route::put('contasbancarias/{contasbancaria}', 'Api\ContasbancariasController@update');
    Route::delete('contasbancarias/{contasbancaria}', 'Api\ContasbancariasController@destroy');
});
