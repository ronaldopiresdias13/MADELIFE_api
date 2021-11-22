<?php

use App\Http\Controllers\Web\Custos\CustosController;
use Illuminate\Support\Facades\Route;

// Route::group(['middleware' => 'auth:api'], function () {
//     Route::prefix('web')->group(function () {
//         // Route::prefix('gestaoOrcamentaria')->group(function () {
//         //     Route::get('servicos/list', 'Api\Web\GestaoOrcamentaria\ServicosController@index');
//         //     Route::get('servicos/listServicosFormacoes', 'Api\Web\GestaoOrcamentaria\ServicosController@listServicosFormacoes');
//         //     Route::post('servicos', 'Api\Web\GestaoOrcamentaria\ServicosController@store');
//         //     Route::get('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@show');
//         //     Route::put('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@update');
//         //     Route::delete('servicos/{servico}', 'Api\Web\GestaoOrcamentaria\ServicosController@destroy');
//         // });
//         Route::get('custos', 'Api\Web\Custos\CustosController@index');
//         Route::post('custos', 'Api\Web\Custos\CustosController@store');
//         Route::get('custos/{custo}', 'Api\Web\Custos\CustosController@show');
//         Route::put('custos/{custo}', 'Api\Web\Custos\CustosController@update');
//         Route::delete('custos/{custo}', 'Api\Web\Custos\CustosController@destroy');
//     });
// });

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('web/custos', [CustosController::class, 'index']);
    Route::post('web/custos', [CustosController::class, 'store']);
    Route::get('web/custos/{custo}', [CustosController::class, 'show']);
    Route::put('web/custos/{custo}', [CustosController::class, 'update']);
    Route::delete('web/custos/{custo}', [CustosController::class, 'destroy']);
    // Route::get('web/custos', [FolgasController::class, 'filtroPorPeriodo']);
    // Route::get('web/custos/aguardando', [FolgasController::class, 'listAguardando']);
    // Route::get('web/custos/aprovadas', [FolgasController::class, 'listAprovadas']);
    // Route::get('web/custos/reprovadas', [FolgasController::class, 'listReprovadas']);
    // Route::get('web/custos/pendentes', [FolgasController::class, 'listPendentes']);
    // Route::post('web/custos/adicionarFolga', [FolgasController::class, 'adicionarFolga']);
    // Route::put('web/custos/aprovarFolga/{folga}', [FolgasController::class, 'aprovarFolga']);
    // Route::put('web/custos/reprovarFolga/{folga}', [FolgasController::class, 'reprovarFolga']);
    // Route::put('web/custos/adicionarSubstituto/{folga}', [FolgasController::class, 'adicionarSubstituto']);
    // Route::get('acaomedicamentos/{acaomedicamento}'   , 'Api\AcaomedicamentosController@show');
    // Route::put('acaomedicamentos/{acaomedicamento}'   , 'Api\AcaomedicamentosController@update');
    // Route::delete('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@destroy');
});
