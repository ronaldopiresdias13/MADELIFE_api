<?php

use App\Http\Controllers\Web\Folgas\FolgasController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('web/folgas'                            , [FolgasController::class, 'index'              ]);
    Route::get('web/folgas/aguardando'                 , [FolgasController::class, 'listAguardando'     ]);
    Route::get('web/folgas/aprovadas'                  , [FolgasController::class, 'listAprovadas'      ]);
    Route::get('web/folgas/reprovadas'                 , [FolgasController::class, 'listReprovadas'     ]);
    Route::get('web/folgas/pendentes'                  , [FolgasController::class, 'listPendentes'      ]);
    Route::post('web/folgas/adicionarFolga'            , [FolgasController::class, 'adicionarFolga'     ]);
    Route::put('web/folgas/aprovarFolga/{folga}'       , [FolgasController::class, 'aprovarFolga'       ]);
    Route::put('web/folgas/reprovarFolga/{folga}'      , [FolgasController::class, 'reprovarFolga'      ]);
    Route::put('web/folgas/adicionarSubstituto/{folga}', [FolgasController::class, 'adicionarSubstituto']);
    // Route::get('acaomedicamentos/{acaomedicamento}'   , 'Api\AcaomedicamentosController@show');
    // Route::put('acaomedicamentos/{acaomedicamento}'   , 'Api\AcaomedicamentosController@update');
    // Route::delete('acaomedicamentos/{acaomedicamento}', 'Api\AcaomedicamentosController@destroy');
});
