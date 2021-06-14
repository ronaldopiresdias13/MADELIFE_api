<?php

use Illuminate\Support\Facades\Route;

Route::get('dadoscontratuais', 'Api\DadoscontratuaisController@index');
Route::post('dadoscontratuais', 'Api\DadoscontratuaisController@store');
Route::get('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@show');
Route::put('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@update');
Route::delete('dadoscontratuais/{dadoscontratual}', 'Api\DadoscontratuaisController@destroy');
