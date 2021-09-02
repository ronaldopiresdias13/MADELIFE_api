<?php

namespace App\Http\Controllers\Api\Web\DepartamentoPessoal;

use App\Http\Controllers\Controller;
use App\Models\Pagamentoexterno;
use App\Models\Pagamentointerno;
use App\Models\Pagamentopessoa;
use App\Models\Pessoa;
use App\Models\Prestador;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
    public function gerarlist(Request $request)
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

        return Pagamentoexterno::with(['pagamentopessoa.pessoa', 'servico', 'ordemservico.orcamento.homecare.paciente.pessoa'])
            ->whereHas('pagamentopessoa', function (Builder $query) use ($datainicio, $datafim) {
                $query->where('situacao', "=", "Criado")->whereBetween('periodo1', [$datainicio, $datafim]);
            })
            ->where('empresa_id', $empresa_id)
            ->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%')
            ->get();
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

        return Pagamentoexterno::with(['pagamentopessoa.pessoa', 'servico', 'ordemservico.orcamento.homecare.paciente.pessoa'])
            ->whereHas('pagamentopessoa', function (Builder $query) use ($datainicio, $datafim) {
                $query->where('situacao', "!=", "Criado")->whereBetween('periodo1', [$datainicio, $datafim]);
            })
            ->where('empresa_id', $empresa_id)
            ->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%')
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
        // return $request;
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
                        'empresa_id'         => $empresa_id,
                        'servico_id'         => $item['servico']['id'],
                        'ordemservico_id'    => $item['ordemservico_id'],
                        'turno'              => $item['periodo'],
                        'valorunitario'      => $item['valorunitario'],
                        'quantidade'         => $item['quantidade'],
                        'pagamentopessoa_id' => Pagamentopessoa::create([
                            'empresa_id'     => $empresa_id,
                            'pessoa_id'      => $pessoa->pessoa_id,
                            'periodo1'       => $item['datainicio'],
                            'periodo2'       => $item['datafim'],
                            'valor'          => $item['subtotal'],
                            'status'         => $item['status'],
                            'observacao'     => $item['observacao'],
                            'situacao'       => $item['situacao'],
                            'proventos'      => $item['proventos'],
                            'descontos'      => $item['descontos'],
                            'valorinss'      => $item['valorinss'],
                            'tipovalorinss'  => $item['tipovalorinss'],
                            'valoriss'       => $item['valoriss'],
                            'tipovaloriss'   => $item['tipovaloriss'],
                            'taxaadm'        => $item['taxaadm'],
                            'tipotaxaadm'    => $item['tipotaxaadm'],
                            'tipopessoa'     => "Prestador Externo"
                        ])->id,
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
                $pagamentopessoa  = Pagamentopessoa::find($item['pagamentopessoa_id']);
                $pagamentopessoa->periodo1         = $item['pagamentopessoa']['periodo1'];
                $pagamentopessoa->periodo2         = $item['pagamentopessoa']['periodo2'];
                $pagamentopessoa->valor            = $item['pagamentopessoa']['valor'];
                $pagamentopessoa->status           = $item['pagamentopessoa']['status'];
                $pagamentopessoa->pessoa_id        = $item['pagamentopessoa']['pessoa_id'];
                $pagamentopessoa->observacao       = $item['pagamentopessoa']['observacao'];
                $pagamentopessoa->situacao         = "Pendente";
                $pagamentopessoa->proventos        = $item['pagamentopessoa']['proventos'];
                $pagamentopessoa->descontos        = $item['pagamentopessoa']['descontos'];
                $pagamentopessoa->save();

                $pagamentoexterno = Pagamentoexterno::find($item['id']);
                $pagamentoexterno->empresa_id       = $item['empresa_id'];
                $pagamentoexterno->servico_id        = $item['servico_id'];
                $pagamentoexterno->ordemservico_id  = $item['ordemservico_id'];
                $pagamentoexterno->quantidade       = $item['quantidade'];
                $pagamentoexterno->turno            = $item['turno'];
                $pagamentoexterno->valorunitario    = $item['valorunitario'];
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
    public function apagarpagamento(Pagamentoexterno $pagamentoexterno)
    {
        $pagamentoexterno->pagamentopessoa->delete();
        $pagamentoexterno->delete();
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
