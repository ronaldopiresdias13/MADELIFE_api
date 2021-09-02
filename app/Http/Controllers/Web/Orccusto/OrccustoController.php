<?php

namespace App\Http\Controllers\Web\Orccusto;

use App\Http\Controllers\Controller;
use App\Models\Orccusto;
use Illuminate\Http\Request;

class OrccustoController extends Controller
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
     * @param  \App\Models\Orccusto  $orccusto
     * @return \Illuminate\Http\Response
     */
    public function show(Orccusto $orccusto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orccusto  $orccusto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orccusto $orccusto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orccusto  $orccusto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orccusto $orccusto)
    {
        $orccusto->delete();
    }
}
