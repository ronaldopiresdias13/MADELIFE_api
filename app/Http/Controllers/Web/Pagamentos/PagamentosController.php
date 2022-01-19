<?php

namespace App\Http\Controllers\Web\Pagamentos;

use App\Http\Controllers\Controller;
use App\Models\Pagamento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PagamentosController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function filtroFluxoDeCaixa(Request $request)
    {
        // return $request;
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        // $empresa_id = 1;
        $pagamentos = Pagamento::with(['conta.pessoa'])
            ->where('ativo', true)
            ->where('status', true)
            ->where('empresa_id', $empresa_id)
            ->where('contasbancaria_id', $request->contasbancaria_id)
            ->whereHas('conta', function (Builder $builder) {
                $builder->where('ativo', true);
            });
        if ($request->pessoa_id) {
            $pagamentos->whereHas('conta', function (Builder $builder) use ($request) {
                $builder->where('pessoa_id', $request->pessoa_id);
            });
        }
        if ($request->tipopessoa) {
            $pagamentos->whereHas('conta', function (Builder $builder) use ($request) {
                $builder->where('tipopessoa', $request->tipopessoa);
            });
        }
        if ($request->data_final) {
            $pagamentos->where('datapagamento', '<=', $request->data_final);
        }
        if ($request->data_ini) {
            $pagamentos->where('datapagamento', '>=', $request->data_ini);
        }

        $pagamentos = $pagamentos->get();
        return $pagamentos;
    }

    public function retornaSaldoAnterior(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $entrada = Pagamento::with(['conta.pessoa'])
            ->where('ativo', true)
            ->where('status', true)
            ->where('empresa_id', $empresa_id)
            ->where('contasbancaria_id', $request->contasbancaria_id)
            ->whereHas('conta', function (Builder $builder) {
                $builder->where('ativo', true);
            });
        if ($request->pessoa_id) {
            $entrada->whereHas('conta', function (Builder $builder) use ($request) {
                $builder->where('pessoa_id', $request->pessoa_id);
            });
        }
        if ($request->tipopessoa) {
            $entrada->whereHas('conta', function (Builder $builder) use ($request) {
                $builder->where('tipopessoa', $request->tipopessoa);
            });
        }
        if ($request->data_final) {
            $entrada->where('datapagamento', '<', $request->data_final);
        }
        $entrada = $entrada->limit(5)->sum('valorpago');
        $saida = Pagamento::with(['conta.pessoa'])
            ->where('ativo', true)
            ->where('status', true)
            ->where('empresa_id', $empresa_id)
            ->where('contasbancaria_id', $request->contasbancaria_id)
            ->whereHas('conta', function (Builder $builder) {
                $builder->where('ativo', true);
            });

        if ($request->pessoa_id) {
            $saida->whereHas('conta', function (Builder $builder) use ($request) {
                $builder->where('pessoa_id', $request->pessoa_id);
            });
        }
        if ($request->tipopessoa) {
            $saida->whereHas('conta', function (Builder $builder) use ($request) {
                $builder->where('tipopessoa', $request->tipopessoa);
            });
        }
        if ($request->data_final) {
            $saida->where('datapagamento', '<', $request->data_final);
        }
        $saida = $saida->sum('valorpago');
        return response()->json([
            'saldo' => [
                'entrada' => $entrada,
                'saida' => $saida,
                // 'total' => (float)$entrada - (float)$saida
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }
}
