<?php


use Illuminate\Support\Facades\Route;

Route::get('orcamentoservicos', 'Api\OrcamentoServicosController@index');
Route::post('orcamentoservicos', 'Api\OrcamentoServicosController@store');
Route::get('orcamentoservicos/{orcamentoServico}', 'Api\OrcamentoServicosController@show');
Route::put('orcamentoservicos/{orcamentoServico}', 'Api\OrcamentoServicosController@update');
Route::delete('orcamentoservicos/{orcamentoServico}', 'Api\OrcamentoServicosController@destroy');
