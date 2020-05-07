<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Prestador;
use App\Pessoa;
use App\Formacao;
use App\User;
use App\Dadosbancario;
use App\Banco;
use App\Cargo;
use App\PrestadorFormacao;
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
        return Prestador::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $prestador = new Prestador;
        $prestador->pessoa       = $request->pessoa;
        $prestador->fantasia     = $request->fantasia;
        $prestador->sexo         = $request->sexo;
        $prestador->pis          = $request->pis;
        $prestador->formacao     = $request->formacao;
        $prestador->cargo        = $request->cargo;
        $prestador->curriculo    = $request->curriculo;
        $prestador->certificado  = $request->certificado;
        $prestador->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function show(Prestador $prestador)
    {
        return $prestador;
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
        $prestador->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestador $prestador)
    {
        $prestador->delete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function migracao(Request $request)
    {
        // dd($request['prestador']);
        $value = $request;
        // dd($request);
        // foreach ($request->all() as $key => $value) {
            // dd($value['prestador']['dadosPf']['nome']);
            // dd($value);
            // dd($prestador);

            // alert($value['prestador']['dadosPf']['cpf']['numero']);

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
                'sexo'     => $value['prestador']['nomeFantasia'],
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
            // dd($prestador->id);

            // dd(
            //     Formacao::firstOrCreate(
            //         [
            //             'descricao' => $value['prestador']['dadosProf']['formacao']['descricao'],
            //         ]
            //     )->id
            // );
            
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

            if ($usercpf || $useremail) {
                
            } else {
                $user = User::create([
                    'cpfcnpj' => $value['prestador']['dadosPf']['cpf']['numero'],
                    'email'   => $value['prestador']['contato']['email'],
                    'pessoa'  => $prestador->pessoa,
                    'password' => bcrypt($value['senha']),
                ]);
            }

            // if (!$usercpf && !$useremail) {
            //     dd('Entrou');
            //     $user = User::create([
            //         'cpfcnpj' => $value['prestador']['dadosPf']['cpf']['numero'],
            //         'email'   => $value['prestador']['contato']['email'],
            //         'pessoa'  => $prestador->pessoa,
            //         'password' => bcrypt($value['senha']),
            //     ]);
            // }
            // dd('Não Entrou');

            // $user = User::firstOrCreate([
            //     'cpfcnpj' => $value['prestador']['dadosPf']['cpf']['numero'],
            //     'email'   => $value['prestador']['contato']['email'],
            // ],
            // [
            //     'pessoa'  => $prestador->pessoa,
            //     'password' => bcrypt($value['senha']),
            // ]);

            // $pessoa_outros = PrestadorFormacao::firstOrCreate([
            //     'pessoa' => $prestador->pessoa,
            //     'outro'  => Outro::firstOrCreate(['nomecampo' => $value['prestador']['dadosProf']['formacao']])->id,
            // ]);

            if($value['prestador']['dadosBancario']['banco'] != null && $value['prestador']['dadosBancario']['banco']['codigo'] != null){
                $dados_bancario = Dadosbancario::firstOrCreate([
                    'banco' => Banco::firstOrCreate(
                        [
                            'codigo' => ($value['prestador']['dadosBancario']['banco']['codigo'] == null || $value['prestador']['dadosBancario']['banco']['codigo'] == "") ? '000' : $value['prestador']['dadosBancario']['banco']['codigo'],
                        ],
                        [
                            'descricao' => ($value['prestador']['dadosBancario']['banco']['codigo'] == null || $value['prestador']['dadosBancario']['banco']['codigo'] == "") ? 'Outros' : $value['prestador']['dadosBancario']['banco']['descricao']
                        ]
                    )->id,
                    'pessoa' => $prestador->pessoa,
                    'agencia'  => $value['prestador']['dadosBancario']['agencia'],
                    'conta'  => $value['prestador']['dadosBancario']['conta'],
                    'digito'  => $value['prestador']['dadosBancario']['digito'],
                    'tipoconta'  => $value['prestador']['dadosBancario']['tipoConta'],
                ]);
            }
        // }

    }
}
