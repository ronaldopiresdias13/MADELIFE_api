<?php

namespace App\Http\Controllers\Api\Web\Compras;

use App\Models\Cotacao;
use App\Models\CotacaoProduto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotacoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAllByEmpresaId(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return Cotacao::with(['profissional.pessoa', 'produtos'])
            ->where('empresa_id', $empresa_id)
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
        DB::transaction(function () use ($request) {
            $cotacao = Cotacao::create([
                'codigo'          => $request['codigo'],
                'profissional_id' => $request['profissional_id'],
                'empresa_id'      => $request['empresa_id'],
                'observacao'      => $request['observacao'],
                'situacao'        => $request['situacao'],
                'motivo'          => $request['motivo'],
            ]);
            if ($request['produtos']) {
                foreach ($request['produtos'] as $key => $produto) {
                    $cotacao_produto = CotacaoProduto::updateOrCreate(
                        [
                            'cotacao_id'          => $cotacao->id,
                            'produto_id'          => $produto['pivot']['produto_id'],
                        ],
                        [
                            'fornecedor_id'       => $produto['pivot']['fornecedor_id'],
                            'unidademedida'       => $produto['pivot']['unidademedida'],
                            'quantidade'          => $produto['pivot']['quantidade'],
                            'quantidadeembalagem' => $produto['pivot']['quantidadeembalagem'],
                            'quantidadetotal'     => $produto['pivot']['quantidadetotal'],
                            'valorunitario'       => $produto['pivot']['valorunitario'],
                            'valortotal'          => $produto['pivot']['valortotal'],
                            'formapagamento'      => $produto['pivot']['formapagamento'],
                            'prazoentrega'        => $produto['pivot']['prazoentrega'],
                            'observacao'          => $produto['pivot']['observacao'],
                            'situacao'            => $produto['pivot']['situacao']
                        ]
                    );
                }
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cotacao  $cotacao
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Cotacao $cotacao)
    {
        $cotacao->produtos;
        return $cotacao;
        // return Cotacao::with('produtos')->find($cotacao->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cotacao  $cotacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cotacao $cotacao)
    {
        DB::transaction(function () use ($request, $cotacao) {
            $cotacao->update([
                'codigo'          => $request['codigo'],
                'profissional_id' => $request['profissional_id'],
                'empresa_id'      => $request['empresa_id'],
                'observacao'      => $request['observacao'],
                'situacao'        => $request['situacao'],
                'motivo'          => $request['motivo'],
            ]);
            if ($request['produtos']) {
                foreach ($request['produtos'] as $key => $produto) {
                    $cotacao_produto = CotacaoProduto::updateOrCreate(
                        [
                            'cotacao_id'          => $cotacao->id,
                            'produto_id'          => $produto['pivot']['produto_id'],
                        ],
                        [
                            'fornecedor_id'       => $produto['pivot']['fornecedor_id'],
                            'unidademedida'       => $produto['pivot']['unidademedida'],
                            'quantidade'          => $produto['pivot']['quantidade'],
                            'quantidadeembalagem' => $produto['pivot']['quantidadeembalagem'],
                            'quantidadetotal'     => $produto['pivot']['quantidadetotal'],
                            'valorunitario'       => $produto['pivot']['valorunitario'],
                            'valortotal'          => $produto['pivot']['valortotal'],
                            'formapagamento'      => $produto['pivot']['formapagamento'],
                            'prazoentrega'        => $produto['pivot']['prazoentrega'],
                            'observacao'          => $produto['pivot']['observacao'],
                            'situacao'            => $produto['pivot']['situacao']
                        ]
                    );
                }
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cotacao  $cotacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cotacao $cotacao)
    {
        $cotacao->ativo = false;
        $cotacao->save();
    }
}
