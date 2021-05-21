<?php

namespace App\Http\Controllers\Web\Contratos;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Orcamento;
use App\Services\ContratoService;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ContratosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        if (!$empresa_id) {
            return 'error';
        }
        $result = Orcamento::with(
            [
                'ordemservico',
                'cidade',
                'cliente.pessoa',
                'homecare.paciente.pessoa',
                'aph.cidade',
                'evento.cidade',
                'remocao.cidadeorigem',
                'remocao.cidadedestino',
                // 'produtos.produto',
                // 'servicos.servico',
                // 'custos'
            ],
        )
            ->where('ativo', true)
            ->where('empresa_id', $empresa_id);

        if ($request->filter_nome) {
            $result->whereHas('homecare.paciente.pessoa', function (Builder $query) use ($request) {
                $query->where('nome', 'like', '%' . $request->filter_nome . '%');
            });

            $result->orWhereHas('remocao', function (Builder $query) use ($empresa_id, $request) {
                $query->where('empresa_id', $empresa_id)
                    ->where('nome', 'like', '%' . $request->filter_nome . '%');
            });
        }

        $result = $result->orderByDesc('id')->paginate($request['per_page'] ? $request['per_page'] : 15);

        if (env("APP_ENV", 'production') == 'production') {
            return $result->withPath(str_replace('http:', 'https:', $result->path()));
        } else {
            return $result;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function listaContratosDoClienteNoPeriodo(Request $request, Cliente $cliente)
    {
        $hoje = getdate();
        $primeiroDia = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-01';
        $d = new DateTime($primeiroDia);
        $ultimoDia = $d->format('Y-m-t');

        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        if (!$empresa_id) {
            return 'error';
        }

        $result = Orcamento::with(
            [
                'homecare.paciente.pessoa',
                'servicos.servico',
                'ordemServico',
                'produtos.produto'
            ],
        )
            // ->whereHas('ordemServico')
            ->whereHas('ordemServico', function (Builder $builder) use ($request, $primeiroDia, $ultimoDia) {
                $builder->where(
                    'fim',
                    '>=',
                    $request->inicio ? $request->inicio : $primeiroDia
                )
                ->where(
                    'inicio',
                    '<=',
                    $request->fim ? $request->fim : $ultimoDia
                );
            })
            ->where('ativo', true)
            ->where('tipo', '!=', 'venda')
            ->where('empresa_id', $empresa_id)
            ->where('cliente_id', $cliente->id);

        if ($request->filter_nome) {
            $result->whereHas('homecare.paciente.pessoa', function (Builder $query) use ($request) {
                $query->where('nome', 'like', '%' . $request->filter_nome . '%');
            });

            $result->orWhereHas('remocao', function (Builder $query) use ($empresa_id, $request) {
                $query->where('empresa_id', $empresa_id)
                    ->where('nome', 'like', '%' . $request->filter_nome . '%');
            });
        }

        $result = $result->orderByDesc('id');

        if ($request['paginate']) {
            $result = $result->paginate($request['per_page'] ? $request['per_page'] : 15);

            if (env("APP_ENV", 'production') == 'production') {
                return $result->withPath(str_replace('http:', 'https:', $result->path()));
            } else {
                return $result;
            }
        } else {
            $result = $result->get();
            return $result;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contratoService = new ContratoService($request);
        return $contratoService->store();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function show(Orcamento $orcamento)
    {
        return Orcamento::with(
            [
                'ordemservico',
                'cidade',
                'cliente.pessoa',
                'homecare.paciente.pessoa',
                'aph.cidade',
                'evento.cidade',
                'remocao.cidadeorigem',
                'remocao.cidadedestino',
                'produtos.produto',
                'servicos.servico',
                'custos'
            ],
        )->find($orcamento->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orcamento $orcamento)
    {
        $contratoService = new ContratoService($request, $orcamento);
        return $contratoService->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orcamento $orcamento)
    {
        $orcamento->update(['ativo' => false]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function prorrogacao(Request $request, Orcamento $orcamento)
    {
        $orcamento->ordemservico()->update(['fim' => $request->datafim]);
    }
}
