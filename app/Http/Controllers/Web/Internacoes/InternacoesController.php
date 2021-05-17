<?php

namespace App\Http\Controllers\Web\Internacoes;

use App\Http\Controllers\Controller;
use App\Models\Internacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternacoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Internacao::with(['paciente'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            Internacao::create($request->all());
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Internacao  $internacao
     * @return \Illuminate\Http\Response
     */
    public function show(Internacao $internacao)
    {
        return $internacao;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Internacao  $internacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Internacao $internacao)
    {
        DB::transaction(function () use ($request, $internacao) {
            $internacao->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Internacao  $internacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Internacao $internacao)
    {
        DB::transaction(function () use ($internacao) {
            $internacao->delete();
        });
    }
}
