<?php

namespace App\Http\Controllers\Api;

use App\Cliente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cliente::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cliente = new Cliente;
        $cliente->pessoa = $request->pessoa;
        $cliente->save();
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        return $cliente;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function migracao(Request $request)
    {
        dd($request);
        $value = $request;
        
        $prestador = Prestador::firstOrCreate([
            'pessoa' => Pessoa::firstOrCreate(
                [
                    'cpfcnpj' => $value['prestador']['dadosPf']['cpf']['numero'],
                ],
                [
                    'nome'        => $value['prestador']['dadosPf']['nome'],
                    'nascimento'  => $value['prestador']['dadosPf']['nascimento'],
                    'tipo'        => 'Prestador',
                    'rgie'        => $value['prestador']['dadosPf']['rg']['numero'],
                    'observacoes' => $value['prestador']['observacoes'],
                    'status'      => $value['prestador']['status'],
                ]
                )->id,
                'fantasia' => $value['prestador']['nomeFantasia'],
                'sexo'     => $value['prestador']['dadosPf']['sexo'],
                'pis'      => $value['prestador']['dadosProf']['pis'],
                'cargo'    => Cargo::firstOrCreate(
                    [
                        'cbo' => '10115', // Verificar código CBO Prestador de serviços
                    ],
                    [
                        'descricao' => 'Oficial general da marinha',  // Verificar código CBO Prestador de serviços
                    ])->id,
                'curriculo'   => $value['prestador']['dadosPf']['curriculo'],
                'certificado' => $value['prestador']['dadosPf']['certificado'],
            ]);
            
            
            $prestador_formacao = PrestadorFormacao::firstOrCreate([
                'prestador' => $prestador->id,
                'formacao'  => Formacao::firstOrCreate(['descricao' => $value['prestador']['dadosProf']['formacao']['descricao']])->id,
            ]);
            
            $usercpf = User::firstWhere(
                'cpfcnpj' , $value['prestador']['dadosPf']['cpf']['numero']
            );
            $useremail = User::firstWhere(
                'email', $value['prestador']['contato']['email']
            );

            
            if ($value['prestador']['contato']['telefone'] != null && $value['prestador']['contato']['telefone'] != "") {
                $pessoa_telefones = PessoaTelefone::firstOrCreate([
                    'pessoa'   => $prestador->pessoa,
                    'telefone' => Telefone::firstOrCreate(
                        [
                            'telefone' => $value['prestador']['contato']['telefone'],
                        ]
                    )->id,
                ]);
            }
            if ($value['prestador']['contato']['celular'] != null && $value['prestador']['contato']['celular'] != "") {
                $pessoa_telefones = PessoaTelefone::firstOrCreate([
                    'pessoa'   => $prestador->pessoa,
                    'telefone' => Telefone::firstOrCreate(
                        [
                            'telefone' => $value['prestador']['contato']['celular'],
                        ]
                    )->id,
                ]);
            }
    }
}
