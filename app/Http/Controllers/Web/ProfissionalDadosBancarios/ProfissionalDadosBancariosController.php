<?php

namespace App\Http\Controllers\Web\ProfissionalDadosBancarios;

use App\Http\Controllers\Controller;
use App\Models\Dadosbancario;
use Illuminate\Http\Request;

class ProfissionalDadosBancariosController extends Controller
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
     * @param  \App\Models\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function show(Dadosbancario $dadosbancario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dadosbancario $dadosbancario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dadosbancario $dadosbancario)
    {
        $dadosbancario->ativo = false;
        $dadosbancario->save();
    }
}
