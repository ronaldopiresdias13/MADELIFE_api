<?php

use Illuminate\Support\Facades\Route;

Route::get('pessoaTelefones', 'Api\PessoaTelefoneController@index');
Route::post('pessoaTelefones', 'Api\PessoaTelefoneController@store');
Route::get('pessoaTelefones/{pessoaTelefone}', 'Api\PessoaTelefoneController@show');
Route::put('pessoaTelefones/{pessoaTelefone}', 'Api\PessoaTelefoneController@update');
Route::delete('pessoaTelefones/{pessoaTelefone}', 'Api\PessoaTelefoneController@destroy');
