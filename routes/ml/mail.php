<?php

use Illuminate\Support\Facades\Route;

/*-------------- Rota de Logs por email --------------*/
Route::post("sendMailLog", "LogsController@sendMailLog");
