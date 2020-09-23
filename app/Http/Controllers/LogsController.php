<?php

namespace App\Http\Controllers;

use App\Mail\LogApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LogsController extends Controller
{
    public function sendMailLog(Request $request)
    {
        Mail::send(new LogApp($request));
    }
}
