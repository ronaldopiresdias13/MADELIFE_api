<?php

namespace App\Http\Controllers\Api\Web\DepartamentoPessoal;

use App\Http\Controllers\Controller;
use App\Models\Pagamentoexterno;
use App\Models\Pagamentointerno;
use App\Models\Pessoa;
use App\Models\Prestador;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagamentoexternosController extends Controller
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
    public function list(Request $request)
    {
        $empresa_id = null;

        if (Auth::check()) {
            if (Auth::user()->pessoa->profissional) {
                $empresa_id = Auth::user()->pessoa->profissional->empresa_id;
            }
        }

        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . ($hoje['mday'] < 10 ? '0' . $hoje['mday'] : $hoje['mday']);
        $datainicio = $request['datainicio'] ? $request['datainicio'] : date("Y-m-01", strtotime($data));
        $datafim    = $request['datafim']    ? $request['datafim']    : date("Y-m-t", strtotime($data));

        return Pagamentoexterno::with(['pessoa', 'servico', 'ordemservico.orcamento.homecare.paciente.pessoa'])
            ->where('empresa_id', $empresa_id)
            ->where('situacao', $request['situacao'])
            ->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%')
            ->whereBetween('datainicio', [$datainicio, $datafim])
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createlist(Request $request)
    {
        // return $request->pagamentos;

        $empresa_id = null;

        if (Auth::check()) {
            if (Auth::user()->pessoa->profissional) {
                $empresa_id = Auth::user()->pessoa->profissional->empresa_id;
            }
        }

        DB::transaction(function () use ($request, $empresa_id) {
            foreach ($request->pagamentos as $key => $item) {
                $pessoa = Prestador::find($item['prestador_id']);
                Pagamentoexterno::create(
                    [
                        'empresa_id'       => $empresa_id,
                        'pessoa_id'        => $pessoa->pessoa_id,
                        'servico_id'        => $item['servico']['id'],
                        'datainicio'       => $item['datainicio'],
                        'datafim'          => $item['datafim'],
                        'ordemservico_id'  => $item['ordemservico_id'],
                        'quantidade'       => $item['quantidade'],
                        'turno'            => $item['periodo'],
                        'valorunitario'    => $item['valorunitario'],
                        'subtotal'         => $item['subtotal'],
                        'status'           => $item['status'],
                        'observacao'       => $item['observacao'],
                        'situacao'         => $item['situacao'],
                        'proventos'        => $item['proventos'],
                        'descontos'        => $item['descontos']
                    ]
                );
            }
        });
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pagamentoexterno  $pagamentoexterno
     * @return \Illuminate\Http\Response
     */
    public function show(Pagamentoexterno $pagamentoexterno)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pagamentoexterno  $pagamentoexterno
     * @return \Illuminate\Http\Response
     */
    public function atualizarPagamentosExternos(Request $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->pagamentos as $key => $item) {
                $pagamentoexterno = Pagamentoexterno::find($item['id']);
                $pagamentoexterno->empresa_id       = $item['empresa_id'];
                $pagamentoexterno->pessoa_id        = $item['pessoa_id'];
                $pagamentoexterno->servico_id        = $item['servico_id'];
                $pagamentoexterno->datainicio       = $item['datainicio'];
                $pagamentoexterno->datafim          = $item['datafim'];
                $pagamentoexterno->ordemservico_id  = $item['ordemservico_id'];
                $pagamentoexterno->quantidade       = $item['quantidade'];
                $pagamentoexterno->turno            = $item['turno'];
                $pagamentoexterno->valorunitario    = $item['valorunitario'];
                $pagamentoexterno->subtotal         = $item['subtotal'];
                $pagamentoexterno->status           = $item['status'];
                $pagamentoexterno->observacao       = $item['observacao'];
                $pagamentoexterno->situacao         = "Pendente";
                $pagamentoexterno->proventos        = $item['proventos'];
                $pagamentoexterno->descontos        = $item['descontos'];
                $pagamentoexterno->save();
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pagamentoexterno  $pagamentoexterno
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pagamentoexterno $pagamentoexterno)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function groupByPagamentoByMesAndEmpresaId(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $pagamentosexternos = Pagamentoexterno::with('pessoa')
            ->where('empresa_id', $empresa_id)
            ->where('status', false)
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->datainicio)->format('Y-m');
            });
        // $pagamentosternos = Pagamentointerno::with('pessoa')
        return $pagamentosexternos;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function atualizarSituacaoPagamentoDiretoria(Request $request)
    {
        // return $request;
        DB::transaction(function () use ($request) {
            foreach ($request['pagamentos'] as $key => $pag) {
                $pagamento = Pagamentoexterno::find($pag['id']);
                $pagamento->situacao = $request['situacao'];
                $pagamento->update();
            }
        });
    }
}
