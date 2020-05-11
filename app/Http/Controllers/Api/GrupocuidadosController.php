<?php

namespace App\Http\Controllers\Api;

use App\Grupocuidado;
use App\Cuidado;
use App\CuidadoGrupocuidado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GrupocuidadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Grupocuidado::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $grupocuidado = new Grupocuidado;
        $grupocuidado->descricao = $request->descricao;
        $grupocuidado->empresa = $request->empresa;
        $grupocuidado->status = $request->staus; 
        $grupocuidado->save(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Grupocuidado  $grupocuidado
     * @return \Illuminate\Http\Response
     */
    public function show(Grupocuidado $grupocuidado)
    {
        return $grupocuidado;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Grupocuidado  $grupocuidado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grupocuidado $grupocuidado)
    {
        $grupocuidado->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Grupocuidado  $grupocuidado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grupocuidado $grupocuidado)
    {
        $grupocuidado->delete();
    }


    public function migracao(Request $request)
    {
        foreach ($request['cuidado'] as $cuidado) {
            $cuidados_grupocuidados = CuidadoGrupocuidado::firstOrCreate([
                'cuidado_id' => Cuidado::firstOrCreate(
                    [
                        'codigo' => $cuidado['codigo'],
                    ],
                    [
                        'descricao' => $request['descricao'],
                        'empresa_id' => 1,
                        'status' => true,
                    ])->id,
                'grupocuidado_id' => Grupocuidado::firstOrCreate(
                    [
                        'codigo' => $request['codigoGrupo'],
                    ],
                    [
                        'descricao' => $request['descricao'],
                        'empresa_id' => 1,
                        'status' => true,
                    ])->id,
                ]);
        }
    }
}
