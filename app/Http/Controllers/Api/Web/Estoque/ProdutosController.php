<?php

namespace App\Http\Controllers\Api\Web\Estoque;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function movimentacaoEstoque(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return DB::select(
            "(SELECT e.`data`, p.descricao, ep.quantidade, ep.valor, 'Entrada' AS movimentacao
                FROM entrada_produto AS ep
                INNER JOIN produtos AS p
                ON p.id = ep.produto_id
                INNER JOIN entradas AS e
                ON e.id = ep.entrada_id 
                where p.empresa_id = ?
                and e.`data` BETWEEN ? AND ?
                )
                UNION all
                (SELECT s.`data`, p.descricao, sp.quantidade, sp.valor, 'Saída' AS movimentacao FROM saida_produto AS sp
                INNER JOIN produtos AS p
                ON p.id = sp.produto_id
                INNER JOIN saidas AS s
                ON s.id = sp.saida_id 
                 where p.empresa_id = ?
                and s.`data` BETWEEN ? AND ?
                )
                ORDER BY `data`",
            [
                $empresa_id,
                $request->data_ini,
                $request->data_fim,
                $empresa_id,
                $request->data_ini,
                $request->data_fim
            ]
        );
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
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function show(Produto $produto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Produto $produto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produto $produto)
    {
        //
    }
}
