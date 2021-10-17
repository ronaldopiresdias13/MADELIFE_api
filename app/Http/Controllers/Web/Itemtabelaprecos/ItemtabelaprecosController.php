<?php

namespace App\Http\Controllers\Web\Itemtabelaprecos;

use App\Http\Controllers\Controller;
use App\Models\Itemtabelapreco;
use Illuminate\Http\Request;

class ItemtabelaprecosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Itemtabelapreco::where('tabela_id', $request->versao_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->itenstabelapreco as $key => $item) {
            $itemtabelapreco = new Itemtabelapreco();
            $itemtabelapreco->versaotabelapreco_id = $item['versaotabelapreco_id'];
            $itemtabelapreco->codigo               = $item['codigo'];
            $itemtabelapreco->tiss                 = $item['tiss'];
            $itemtabelapreco->tuss                 = $item['tuss'];
            $itemtabelapreco->nome                 = $item['nome'];
            $itemtabelapreco->preco                = $item['preco'];
            $itemtabelapreco->save();
        }

        return $itemtabelapreco;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Itemtabelapreco  $itemtabelapreco
     * @return \Illuminate\Http\Response
     */
    public function show(Itemtabelapreco $itemtabelapreco)
    {
        return $itemtabelapreco;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Itemtabelapreco  $itemtabelapreco
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Itemtabelapreco $itemtabelapreco)
    {
        $itemtabelapreco->versaotabelapreco_id = $request->versaotabelapreco_id;
        $itemtabelapreco->codigo               = $request->codigo;
        $itemtabelapreco->tiss                 = $request->tiss;
        $itemtabelapreco->tuss                 = $request->tuss;
        $itemtabelapreco->nome                 = $request->nome;
        $itemtabelapreco->preco                = $request->preco;
        $itemtabelapreco->save();
        return $itemtabelapreco;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Itemtabelapreco  $itemtabelapreco
     * @return \Illuminate\Http\Response
     */
    public function destroy(Itemtabelapreco $itemtabelapreco)
    {
        $itemtabelapreco->delete;
    }
}
