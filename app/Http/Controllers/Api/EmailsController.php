<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\email;
use Illuminate\Http\Request;

class EmailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Email::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $email = new Email;
        $email->email = $request->email;
        $email->tipo  = $request->tipo ;
        $email->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(email $email)
    {
        return $email;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\email  $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, email $email)
    {
        $email->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(email $email)
    {
        $email->delete();
    }
}
