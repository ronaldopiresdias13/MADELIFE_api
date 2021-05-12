<?php


use Illuminate\Support\Facades\Route;

Route::post('comentariosmedicao', 'Api\Web\ComentariomedicaoController@store');
Route::get('comentariosmedicao/buscaComentariosPorIdMedicao/{medicao}', 'Api\Web\ComentariomedicaoController@buscaComentariosPorIdMedicao');
