<?php

use Illuminate\Support\Facades\Route;

Route::get('pessoaEnderecos', 'Api\PessoaEnderecoController@index');
Route::post('pessoaEnderecos', 'Api\PessoaEnderecoController@store');
Route::get('pessoaEnderecos/{pessoaEndereco}', 'Api\PessoaEnderecoController@show');
Route::put('pessoaEnderecos/{pessoaEndereco}', 'Api\PessoaEnderecoController@update');
Route::delete('pessoaEnderecos/{pessoaEndereco}', 'Api\PessoaEnderecoController@destroy');
