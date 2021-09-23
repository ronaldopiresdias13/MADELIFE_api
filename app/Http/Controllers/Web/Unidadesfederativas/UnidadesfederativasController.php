<?php

namespace App\Http\Controllers\Web\Unidadesfederativas;

use App\Http\Controllers\Controller;
use App\Models\Unidadefederativa;
use Illuminate\Http\Request;

class UnidadesfederativasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Unidadefederativa::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unidadefederativa  $unidadefederativa
     * @return \Illuminate\Http\Response
     */
    public function show(Unidadefederativa $unidadefederativa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unidadefederativa  $unidadefederativa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unidadefederativa $unidadefederativa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unidadefederativa  $unidadefederativa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidadefederativa $unidadefederativa)
    {
        //
    }
}
