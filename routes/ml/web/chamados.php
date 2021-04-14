<?php

use Illuminate\Support\Facades\Route;

Route::prefix('chat')->group(function () {
    Route::get('chamados_enfermagem', 'Api\Web\Chat\ChamadosController@chamados_enfermagem');
    Route::get('chamados_ti', 'Api\Web\Chat\ChamadosController@chamados_ti');

    Route::get('get_pessoas_externo', 'Api\Web\Chat\ChamadosController@get_pessoas_externo');
    Route::post('criarchamado_atendente_enfermagem', 'Api\Web\Chat\ChamadosController@criarchamado_atendente_enfermagem');

    Route::post('finalizarchamado_enfermagem', 'Api\Web\Chat\ChamadosController@finalizarchamado_enfermagem');

    Route::post('criarchamado_atendente_ti', 'Api\Web\Chat\ChamadosController@criarchamado_atendente_ti');

    Route::post('finalizarchamado_ti', 'Api\Web\Chat\ChamadosController@finalizarchamado_ti');
});
