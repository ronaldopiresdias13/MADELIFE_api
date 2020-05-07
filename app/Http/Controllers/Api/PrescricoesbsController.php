<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Prescricaob;
use Illuminate\Http\Request;

class PrescricoesbsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Prescricaob::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $prescricaob = new Prescricaob;
        $prescricaob->descricao = $request->descricao;
        $prescricaob->descricao = $request->descricao;
        $prescricaob->descricao = $request->descricao;
        $prescricaob->descricao = $request->descricao;
        $prescricaob->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Prescricaob  $prescricaob
     * @return \Illuminate\Http\Response
     */
    public function show(Prescricaob $prescricaob)
    {
        return $prescricaob;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Prescricaob  $prescricaob
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prescricaob $prescricaob)
    {
        $prescricaob->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prescricaob  $prescricaob
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prescricaob $prescricaob)
    {
        $prescricaob->delete();
    }
}
