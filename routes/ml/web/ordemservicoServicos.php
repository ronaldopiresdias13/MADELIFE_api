<?php


use Illuminate\Support\Facades\Route;

Route::get('ordemservicoServicos', 'Api\OrdemservicoServicoController@index');
Route::post('ordemservicoServicos', 'Api\OrdemservicoServicoController@store');
Route::get('ordemservicoServicos/{ordemservicoServico}', 'Api\OrdemservicoServicoController@show');
Route::put('ordemservicoServicos/{ordemservicoServico}', 'Api\OrdemservicoServicoController@update');
Route::delete('ordemservicoServicos/{ordemservicoServico}', 'Api\OrdemservicoServicoController@destroy');
