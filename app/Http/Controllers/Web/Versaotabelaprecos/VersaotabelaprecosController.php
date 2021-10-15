<?php

namespace App\Http\Controllers\Web\Versaotabelaprecos;

use App\Http\Controllers\Controller;
use App\Models\Versaotabelapreco;
use Illuminate\Http\Request;

class VersaotabelaprecosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Versaotabelapreco::where('tabela_id', $request->tabela_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $versaotabelapreco = new Versaotabelapreco();
        $versaotabelapreco->tabelapreco_id = $request->tabelapreco_id;
        $versaotabelapreco->versao         = $request->versao;
        $versaotabelapreco->save();
        return $versaotabelapreco;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Versaotabelapreco  $versaotabelapreco
     * @return \Illuminate\Http\Response
     */
    public function show(Versaotabelapreco $versaotabelapreco)
    {
        return $versaotabelapreco;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Versaotabelapreco  $versaotabelapreco
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Versaotabelapreco $versaotabelapreco)
    {
        $versaotabelapreco->tabelapreco_id = $request->tabelapreco_id;
        $versaotabelapreco->versao         = $request->versao;
        $versaotabelapreco->save();
        return $versaotabelapreco;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Versaotabelapreco  $versaotabelapreco
     * @return \Illuminate\Http\Response
     */
    public function destroy(Versaotabelapreco $versaotabelapreco)
    {
        $versaotabelapreco->delete;
    }
}
