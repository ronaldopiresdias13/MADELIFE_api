<?php

use Illuminate\Support\Facades\Route;

Route::get('pessoaEmails', 'Api\PessoaEmailController@index');
Route::post('pessoaEmails', 'Api\PessoaEmailController@store');
Route::get('pessoaEmails/{pessoaEmail}', 'Api\PessoaEmailController@show');
Route::put('pessoaEmails/{pessoaEmail}', 'Api\PessoaEmailController@update');
Route::delete('pessoaEmails/{pessoaEmail}', 'Api\PessoaEmailController@destroy');
