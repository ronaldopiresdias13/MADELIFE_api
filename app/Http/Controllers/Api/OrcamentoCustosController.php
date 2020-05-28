<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Orcamentocusto;
use Illuminate\Http\Request;

class OrcamentoCustosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Orcamentocusto  $orcamentocusto
     * @return \Illuminate\Http\Response
     */
    public function show(Orcamentocusto $orcamentocusto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Orcamentocusto  $orcamentocusto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orcamentocusto $orcamentocusto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Orcamentocusto  $orcamentocusto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orcamentocusto $orcamentocusto)
    {
        $orcamentocusto->delete();
    }
}
