<?php

use App\Http\Controllers\Api_V2_0\ML_Additions\AdditionsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('web')->group(function () {
    //    Route::get('budgets', [BudgetsController::class, 'index']);
       Route::post('additions', [AdditionsController::class, 'store']);
    //    Route::put('budget/{budget}', [BudgetsController::class, 'update']);
       Route::get('additions/contract/{contract}', [AdditionsController::class,'getAdditionsByContract_id']);
    //    Route::put('budget/situation/{budget}', [BudgetsController::class, 'alterarSituacao']);
    //    Route::get('budget/{budget}', [BudgetsController::class, 'show']);
    //    Route::delete('budgets/excluirItemPacoteServico/{budgetservice}', [BudgetsController::class, 'excluirItemPacoteServico']);
    //    Route::delete('budgets/excluirItemPacoteProduto/{packageProduct}', [BudgetsController::class, 'excluirItemPacoteProduto']);
    });
});
// Route::get('budgets', [BudgetsController::class, 'index']);
