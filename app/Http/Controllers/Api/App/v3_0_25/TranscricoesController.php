<?php

namespace App\Http\Controllers\Api\App\v3_0_25;

use App\Http\Controllers\Controller;
use App\Models\Horariomedicamento;
use App\Models\Ordemservico;
use App\Models\Transcricao;
use App\Models\TranscricaoProduto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TranscricoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listTranscricoesByEscalaId(Ordemservico $ordemservico)
    {
        // $h = Horariomedicamento::with([
        //     'itensTranscricao.produto',
        //     'itensTranscricao.acoesmedicamentos',
        //     'itensTranscricao.transcricao',
        // ])->whereHas('itensTranscricao', function (Builder $query) use ($ordemservico) {
        //     $query->whereHas('transcricao', function (Builder $query) use ($ordemservico) {
        //         $query->where('ordemservico_id', $ordemservico);
        //     });
        // })
        //     ->get();
        // return $h;
        $tp = TranscricaoProduto::with(
            [
                'produto:id,descricao',
                'horariomedicamentos',
                'acoesmedicamentos'
            ]
        )
            ->whereHas('transcricao', function (Builder $query) use ($ordemservico) {
                $query->where('ordemservico_id', $ordemservico['id']);
            })
            ->get();
        return $tp;
        return Transcricao::with(['itensTranscricao.produto', 'itensTranscricao.horariomedicamentos', 'itensTranscricao.acoesmedicamentos'])
            ->where('ordemservico_id', $ordemservico['id'])
            ->where('ativo', true)
            ->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listMedicamentosByPaciente(Ordemservico $ordemservico)
    {
        return Transcricao::with(['itensTranscricao.produto:id,descricao', 'itensTranscricao.horariomedicamentos'])
            ->where('ordemservico_id', $ordemservico['id'])
            ->where('ativo', true)
            ->get();
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
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function show(Transcricao $transcricao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transcricao $transcricao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transcricao $transcricao)
    {
        //
    }
}
