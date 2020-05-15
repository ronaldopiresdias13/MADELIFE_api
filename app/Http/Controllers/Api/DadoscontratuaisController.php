<?php

namespace App\Http\Controllers\Api;

use App\Dadoscontratuais;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DadoscontratuaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itens = new Dadoscontratuais;
        
        if ($request->where) {
            foreach ($request->where as $key => $where) {
                $itens->where(
                    ($where['coluna'   ])? $where['coluna'   ] : 'id',
                    ($where['expressao'])? $where['expressao'] : 'like',
                    ($where['valor'    ])? $where['valor'    ] : '%'
                );
            }
        }

        if ($request->order) {
            foreach ($request->order as $key => $order) {
                $itens->orderBy(
                    ($order['coluna'])? $order['coluna'] : 'id',
                    ($order['tipo'  ])? $order['tipo'  ] : 'asc'
                );
            }
        }
        
        $itens = $itens->get();
        
        if ($request->adicionais) {
            foreach ($itens as $key => $iten) {
                foreach ($request->adicionais as $key => $adic) {
                    $iten[$adic];
                }
            }
        }

        return $itens;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dadoscontratuais = new Dadoscontratuais;
        $dadoscontratuais->tiposalario = $request->tiposalario;
        $dadoscontratuais->salario = $request->salario;
        $dadoscontratuais->cargahoraria = $request->cargahoraria;
        $dadoscontratuais->insalubridade = $request->insalubridade;
        $dadoscontratuais->percentualinsalubridade = $request->percentualinsalubridade;
        $dadoscontratuais->admissao = $request->admissao;
        $dadoscontratuais->demissao = $request->demissao;
        $dadoscontratuais->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dadoscontratuais  $dadoscontratuais
     * @return \Illuminate\Http\Response
     */
    public function show(Dadoscontratuais $dadoscontratuais)
    {
        return $dadoscontratuais;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dadoscontratuais  $dadoscontratuais
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dadoscontratuais $dadoscontratuais)
    {
        $dadoscontratuais->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dadoscontratuais  $dadoscontratuais
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dadoscontratuais $dadoscontratuais)
    {
        $dadoscontratuais->delete();
    }
}
