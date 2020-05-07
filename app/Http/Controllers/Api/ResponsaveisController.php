<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Responsavel;
use Illuminate\Http\Request;

class ResponsaveisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Responsavel::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $responsavel = new Responsavel;
        $responsavel->pessoa = $request->pessoa;
        $responsavel->parentesco  = $request->parentesco ;
        $responsavel->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Responsavel  $responsavel
     * @return \Illuminate\Http\Response
     */
    public function show(Responsavel $responsavel)
    {
        return $responsavel;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Responsavel  $responsavel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Responsavel $responsavel)
    {
        $responsavel->update($responsavel-all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Responsavel  $responsavel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Responsavel $responsavel)
    {
        $responsavel->delete();
    }
}
