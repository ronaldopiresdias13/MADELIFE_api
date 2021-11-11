<?php

namespace App\Http\Controllers\Web\Folgas;

use App\Http\Controllers\Controller;
use App\Models\Escala;
use App\Models\Folga;
use Illuminate\Database\Eloquent\Builder;
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
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $folgas = Folga::with([
            'escala.ordemservico.orcamento.homecare.paciente.pessoa',
            'escala.ordemservico.orcamento.cliente.pessoa',
            'prestador.pessoa',
            'substituto.pessoa',
            'escala'
        ])

            ->where('empresa_id', $empresa_id)
            ->orderByDesc('created_at')
            ->get();

        return $folgas;
    }
    public function filtroPorPeriodo(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $folgas = Folga::with([
            'escala.ordemservico.orcamento.homecare.paciente.pessoa',
            'escala.ordemservico.orcamento.cliente.pessoa',
            'prestador.pessoa',
            'substituto.pessoa',
            'escala',
        ]);
        if ($request->data_final) {
            $folgas = $folgas->whereHas('escala', function (Builder $query) use ($request, $folgas) {
                $query->where('datasaida', '<=', $request->data_final ? $request->data_final : $folgas);
            });
        }
        if ($request->data_ini) {
            $folgas = $folgas->whereHas('escala', function (Builder $query) use ($request, $folgas) {
                $query->where('dataentrada', '>=', $request->data_ini ? $request->data_ini : $folgas);
            });
        }
        if ($request->cliente_id) {
            $folgas = $folgas->whereHas('escala.ordemservico.orcamento', function (Builder $query) use ($request) {
                $query->where('cliente_id', $request->cliente_id);
            });
        }
        $folgas->where('empresa_id', $empresa_id);
        $folgas = $folgas->get();

        return $folgas;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAguardando(Request $request)
    {
        $folgas = Folga::with('escala')
            ->where('empresa_id', $request->empresa_id)
            ->where('aprovada', null)
            ->get();

        return $folgas;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAprovadas(Request $request)
    {
        $folgas = Folga::with('escala')
            ->where('empresa_id', $request->empresa_id)
            ->where('aprovada', true)
            ->get();

        return $folgas;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listReprovadas(Request $request)
    {
        $folgas = Folga::with('escala')
            ->where('empresa_id', $request->empresa_id)
            ->where('aprovada', false)
            ->get();

        return $folgas;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listPendentes(Request $request)
    {
        $folgas = Folga::with('escala')
            ->where('empresa_id', $request->empresa_id)
            ->where('substituto', null)
            ->get();

        return $folgas;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function solicitarFolga(Request $request)
    {
        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . ($hoje['mday'] < 10 ? '0' . $hoje['mday'] : $hoje['mday']);

        DB::transaction(function () use ($request, $data) {
            $escala = Escala::find($request->escala_id);

            $folga = new Folga();
            $folga->empresa_id      = $escala->empresa_id;
            $folga->escala_id       = $escala->id;
            $folga->prestador_id    = $escala->prestador_id;
            $folga->datasolicitacao = $data;
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
        // return $request->escalas;
        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . ($hoje['mday'] < 10 ? '0' . $hoje['mday'] : $hoje['mday']);

        DB::transaction(function () use ($request, $data) {
            foreach ($request->escalas as $key => $escala) {
                $e = Escala::find($escala['id']);
                // if (!$escala->folga) {
                // if($escala->empresa_id){
                $folga = new Folga();
                $folga->empresa_id    = $e->empresa_id;
                $folga->escala_id     = $e->id;
                $folga->prestador_id  = $e->prestador_id;
                $folga->tipo          = $escala['tipo'];
                $folga->aprovada      = true;
                $folga->situacao      = $e->dataentrada == $data ? 'Emergente' : 'Pendente';
                $folga->dataaprovacao = $data;
                $folga->save();

                $e->folga = true;
                $e->save();
                // }
                // }
            }

            // $folga = new Folga();
            // $folga->empresa_id    = $escala->empresa_id;
            // $folga->escala_id     = $escala->id;
            // $folga->prestador_id  = $escala->prestador_id;
            // $folga->aprovada      = true;
            // $folga->dataaprovacao = $data;
            // $folga->save();

            // $escala->folga = true;
            // $escala->save();
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
    public function reprovarFolga(Request $request, Folga $folga)
    {
        DB::transaction(function () use ($folga) {
            $folga->aprovada = false;
            $folga->save();

            $escala = Escala::find($folga->escala_id);
            $escala->folga = false;
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
            $folga->substituto = $request['substituto']['id'];
            $folga->save();

            $escala = Escala::find($folga->escala_id);
            $escala->prestador_id     = $request['substituto']['id'];
            $escala->valorhoradiurno  = $request['escala']['valorhoradiurno'];
            $escala->valorhoranoturno = $request['escala']['valorhoranoturno'];
            $escala->observacao       = $request['escala']['observacao'];
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
