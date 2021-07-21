<?php

namespace App\Http\Controllers\Web\Folgas;

use App\Http\Controllers\Controller;
use App\Models\Escala;
use App\Models\Folga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FolgasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $folgas = Folga::with('escala')
        ->where('empresa_id', $request->empresa_id)
        ->get();

        return $folgas;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function solicitarFolga(Request $request)
    {
        DB::transaction(function () use ($request) {
            $escala = Escala::find($request->escala_id);

            $folga = new Folga();
            $folga->empresa_id      = $escala->empresa_id;
            $folga->escala_id       = $escala->id;
            $folga->prestador_id    = $escala->prestador_id;
            $folga->datasolicitacao = $request->datasolicitacao;
            $folga->save();
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adicionarFolga(Request $request)
    {
        DB::transaction(function () use ($request) {
            $escala = Escala::find($request->escala_id);

            $folga = new Folga();
            $folga->empresa_id    = $escala->empresa_id;
            $folga->escala_id     = $escala->id;
            $folga->prestador_id  = $escala->prestador_id;
            $folga->aprovada      = true;
            $folga->dataaprovacao = $request->dataaprovacao;
            $folga->save();

            $escala->folga = true;
            $escala->save();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Folga  $folga
     * @return \Illuminate\Http\Response
     */
    public function show(Folga $folga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folga  $folga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folga $folga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folga  $folga
     * @return \Illuminate\Http\Response
     */
    public function aprovarFolga(Request $request, Folga $folga)
    {
        DB::transaction(function () use ($folga) {
            $folga->aprovada = true;
            $folga->save();

            $escala = Escala::find($folga->escala_id);
            $escala->folga = true;
            $escala->save();
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folga  $folga
     * @return \Illuminate\Http\Response
     */
    public function adicionarSubstituto(Request $request, Folga $folga)
    {
        DB::transaction(function () use ($request, $folga) {
            $folga->substituto = $request->substituto;
            $folga->save();

            $escala = Escala::find($folga->escala_id);
            $escala->substituto = $request->substituto;
            $escala->save();
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Folga  $folga
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folga $folga)
    {
        //
    }
}
