<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Ordemservico;
use Illuminate\Http\Request;

class OrdemservicosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new Ordemservico;
        
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
        $ordemservico = new Ordemservico;
        $ordemservico->codigo = new $request->codigo;
        $ordemservico->tipo = new $request->tipo;
        $ordemservico->orcamento_id = new $request->orcamento_id;
        $ordemservico->inicio = new $request->inicio;
        $ordemservico->fim = new $request->fim;
        $ordemservico->status = new $request->status;
        $ordemservico->montagemequipe = new $request->montagemequipe;
        $ordemservico->realizacaoprocedimento = new $request->realizacaoprocedimento;
        $ordemservico->nome = new $request->nome;
        $ordemservico->sexo = new $request->sexo;
        $ordemservico->nascimento = new $request->nascimento;
        $ordemservico->cpfcnpj = new $request->cpfcnpj;
        $ordemservico->rgie = new $request->rgie;
        $ordemservico->endereco1 = new $request->endereco1;
        $ordemservico->cidade1 = new $request->cidade1;
        $ordemservico->cep1 = new $request->cep1;
        $ordemservico->endereco2 = new $request->endereco2;
        $ordemservico->cidade2 = new $request->cidade2;
        $ordemservico->cep2 = new $request->cep2;
        $ordemservico->contato = new $request->contato;
        $ordemservico->email = new $request->email;
        $ordemservico->profissional_id = new $request->profissional_id;
        $ordemservico->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function show(Ordemservico $ordemservico)
    {
        return $ordemservico;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ordemservico $ordemservico)
    {
        $ordemservico->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordemservico $ordemservico)
    {
        $ordemservico->delete();
    }

    public function migracao(Request $request){
        $ordemservico = Ordemservico::firstOrCreate([
            'codigo'=> '',
            'tipo' => 'Paciente',
            'orcamento_id' => $request['supervisor'],
            'inicio' => null,
            'fim' => null,
            'status' => $request['status'],
            'montagemequipe' => true,
            'realizacaoprocedimento' => false,
            'nome' => $request['nome'],
            'sexo' => $request['sexo'],
            'nascimento' => $request['nascimento'],
            'cpfcnpj' => $request['dataInicio'],
            'rgie' => $request['dataFim'],
            'endereco1' => $request['endereco']['rua'],
            'cidade1' => $request['endereco']['cidade'],
            'cep1' => $request['endereco']['cep'],
            'endereco2' => '',
            'cidade2' => '',
            'cep2' => '',
            'contato' => $request['contato']['celular'],
            'email' => $request['contato']['email'],
            'profissional_id' => null,

        ]);
    }
}
