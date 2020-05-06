<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Profissional;
use Illuminate\Http\Request;

class ProfissionaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Profissional::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $profissional = new Profissional;
        $profissional->pessoa = $request->pessoa;
        $profissional->pessoafisica = $request->pessoafisica;
        $profissional->sexo = $request->sexo;
        $profissional->setor = $request->setor;
        $profissional->cargo = $request->cargo;
        $profissional->pis = $request->pis;
        $profissional->numerocarteiratrabalho = $request->numerocarteiratrabalho;
        $profissional->numerocnh = $request->numerocnh;
        $profissional->categoriacnh = $request->categoriacnh;
        $profissional->validadecnh = $request->validadecnh;
        $profissional->numerotituloeleitor = $request->numerotituloeleitor;
        $profissional->zonatituloeleitor = $request->zonatituloeleitor;
        $profissional->secaotituloeleitor = $request->secaotituloeleitor;
        $profissional->dadoscontratuais = $request->dadoscontratuais;
        $profissional->save();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function show(Profissional $profissional)
    {
        return $profissional;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profissional $profissional)
    {
        $profissional->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profissional $profissional)
    {
        $profissional->delete();
    }
}
