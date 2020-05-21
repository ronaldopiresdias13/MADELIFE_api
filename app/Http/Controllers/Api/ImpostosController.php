<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imposto;
use Illuminate\Http\Request;

class ImpostosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Imposto::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Imposto::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Imposto  $imposto
     * @return \Illuminate\Http\Response
     */
    public function show(Imposto $imposto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Imposto  $imposto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Imposto $imposto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Imposto  $imposto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Imposto $imposto)
    {
        //
    }
}
