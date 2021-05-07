<?php

use Illuminate\Support\Facades\Route;


Route::delete('transcricaoprodutos/{transcricao_produto}', 'Api\TranscricaoProdutoController@destroy');
