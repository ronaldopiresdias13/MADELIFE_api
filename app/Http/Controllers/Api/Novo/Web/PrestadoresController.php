<?php

namespace App\Http\Controllers\Api\Novo\Web;

use App\Http\Controllers\Controller;
use App\Models\Prestador;
use Illuminate\Http\Request;

class PrestadoresController extends Controller
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
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function show(Prestador $prestador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prestador $prestador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestador $prestador)
    {
        //
    }

    /*-------------------- ROTAS CUSTON --------------------*/

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listaDePrestadoresComFiltro(Request $request)
    {
        $prestadores = Prestador::with([
            'pessoa:id,nome',
            'formacoes:formacoes.id,descricao',
            'pessoa.conselhos:conselhos.id,instituicao,numero,uf,pessoa_id',
            'pessoa.enderecos.cidade',
            'pessoa.telefones:telefones.id,telefone'
        ])
            ->get(['id', 'pessoa_id']);

        $result = [];

        foreach ($prestadores as $key => $prestador) {
            $inserir = true;

            if ($inserir && $request['nome']) {
                if (str_contains(strtolower($prestador->pessoa->nome), strtolower($request['nome']))) {
                    $inserir = true;
                } else {
                    $inserir = false;
                }
            }
            if ($inserir && $request['formacao']) {
                $contain = false;
                foreach ($prestador->formacoes as $key => $formacao) {
                    if (str_contains(strtolower($formacao->descricao), strtolower($request['formacao']))) {
                        $contain = true;
                    }
                }
                if ($contain) {
                    $inserir = true;
                } else {
                    $inserir = false;
                }
            }
            if ($inserir && $request['cidade']) {
                if ($prestador->pessoa->enderecos) {
                    $contain = false;
                    foreach ($prestador->pessoa->enderecos as $key => $endereco) {
                        if ($endereco->cidade) {
                            if (str_contains(strtolower($endereco->cidade->nome), strtolower($request['cidade']))) {
                                $contain = true;
                            }
                        }
                    }
                    if ($contain) {
                        $inserir = true;
                    } else {
                        $inserir = false;
                    }
                } else {
                    $inserir = false;
                }
            }

            if ($inserir && ($request['nome'] || $request['formacao'] || $request['cidade'])) {
                array_push($result, $prestador);
            }
        }

        return $result;
    }
}
