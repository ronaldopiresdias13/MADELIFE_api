<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\Comentariomedicao;
use App\Models\Medicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComentariomedicaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            Comentariomedicao::create([
                'medicoes_id' => $request['medicoes_id'],
                'pessoa_id'   => $request['pessoa_id'],
                'comentario'  => $request['comentario'],
                'data'        => $request['data'],
                'hora'        => $request['hora'],
                'situacao'    => "Enviado",
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comentariomedicao  $comentariomedicao
     * @return \Illuminate\Http\Response
     */
    public function buscaComentariosPorIdMedicao(Medicao $medicao)
    {
        return Comentariomedicao::with(['pessoa'])
            ->where('medicoes_id', $medicao)
            ->orderBy(['data', 'hora'])
            ->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comentariomedicao  $comentariomedicao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comentariomedicao $comentariomedicao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comentariomedicao  $comentariomedicao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comentariomedicao $comentariomedicao)
    {
        //
    }
}
