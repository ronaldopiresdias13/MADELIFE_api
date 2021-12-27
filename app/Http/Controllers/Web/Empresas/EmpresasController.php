<?php

namespace App\Http\Controllers\Web\Empresas;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\EmpresaDados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresasController extends Controller
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
        DB::transaction(function () use ($request) {
            $empresa = Empresa::create([
                'razao'                    => $request['razao'],
                'cnpj'                     => $request['cnpj'],
                'ie'                       => $request['ie'],
                'registroANS'              => $request['registroANS'],
                'logo'                     => $request['logo'],
                'logopdf'                  => $request['logopdf'],
                'tiss_sequencialTransacao' => $request['tiss_sequencialTransacao'],
                'CNES'                     => $request['CNES'],
                'quantidadepaciente'       => $request['quantidadepaciente'],
                // 'quantidadead'             => $request['quantidadead'],
                // 'valorad'                  => $request['valorad'],
                // 'quantidadeid'             => $request['quantidadeid'],
                // 'valorid'                  => $request['valorid'],
                'telefone'                 => $request['telefone'],
                'celular'                  => $request['celular'],
                'endereco'                 => $request['endereco'],
                'site'                     => $request['site'],
                'email'                    => $request['email'],
            ]);
            EmpresaDados::firstOrCreate(
                [
                    'codigo'              =>  $request['empresa_dado']['codigo'],
                    'agencia'             =>  $request['empresa_dado']['agencia'],
                    'digito_agencia'      =>  $request['empresa_dado']['digito_agencia'],
                    'conta'               => $request['empresa_dado']['conta'],
                    'digito_conta'        =>  $request['empresa_dado']['digito_conta'],
                    'convenio'            => $request['empresa_dado']['convenio'],
                    'convenio_externo'    => $request['empresa_dado']['convenio_externo'],
                    'nome'                =>  $request['empresa_dado']['nome'],
                    'nome_empresa'        => $empresa->razao,
                    'cnpj'                => $empresa->cnpj,
                    'empresa_id'          => $empresa->id,
                ]
            );
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Empresa $empresa)
    {
        // $user = $request->user();
        // $empresa_id = $user->pessoa->profissional->empresa_id;
        $empresa->empresa_dado;
        // $result = Empresa::find($empresa_id);
        return $empresa;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresa $empresa)
    {
        $empresa =  DB::transaction(function () use ($request, $empresa) {
            $empresa->update([
                'razao'                    => $request['razao'],
                'cnpj'                     => $request['cnpj'],
                'ie'                       => $request['ie'],
                'registroANS'              => $request['registroANS'],
                'logo'                     => $request['logo'],
                // 'logopdf'                  => $request['logopdf'],
                'tiss_sequencialTransacao' => $request['tiss_sequencialTransacao'],
                'CNES'                     => $request['CNES'],
                'quantidadepaciente'       => $request['quantidadepaciente'],
                // 'quantidadead'             => $request['quantidadead'],
                // 'valorad'                  => $request['valorad'],
                // 'quantidadeid'             => $request['quantidadeid'],
                // 'valorid'                  => $request['valorid'],
                'telefone'                 => $request['telefone'],
                'celular'                  => $request['celular'],
                'endereco'                 => $request['endereco'],
                'site'                     => $request['site'],
                'email'                    => $request['email'],
            ]);
            EmpresaDados::updateOrCreate(
                [
                    'id'                 => $request['empresa_dado']['id']
                ],
                [
                    'codigo'              =>  $request['empresa_dado']['codigo'],
                    'agencia'             =>  $request['empresa_dado']['agencia'],
                    'digito_agencia'      =>  $request['empresa_dado']['digito_agencia'],
                    'conta'               => $request['empresa_dado']['conta'],
                    'digito_conta'        =>  $request['empresa_dado']['digito_conta'],
                    'convenio'            => $request['empresa_dado']['convenio'],
                    'convenio_externo'    => $request['empresa_dado']['convenio_externo'],
                    'nome'                =>  $request['empresa_dado']['nome'],
                    'nome_empresa'        => $empresa->razao,
                    'cnpj'                => $empresa->cnpj,
                    'empresa_id'          => $empresa->id,
                ]
            );
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {
        $empresa->ativo = false;
        $empresa->save();
    }
}
