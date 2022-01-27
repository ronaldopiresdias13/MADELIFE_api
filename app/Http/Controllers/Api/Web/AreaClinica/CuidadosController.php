<?php

namespace App\Http\Controllers\Api\Web\AreaClinica;

use App\Models\Cuidado;
use App\Models\Empresa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuidadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa->id;
        return Cuidado::where('empresa_id', $empresa_id)
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
        
        $cuidado = DB::transaction(function () use ($request) {
            $user = $request->user();
            $empresa_id = $user->pessoa->profissional->empresa->id;
            $cuidado = Cuidado::create([
                'descricao' => $request['descricao'],
                'codigo' => $request['codigo'],
                'empresa_id' => $empresa_id,
                'status' => $request['status'],
            ]);

            $cuidado=$cuidado->fresh();
            return $cuidado;
        });

        return $cuidado;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Cuidado $cuidado)
    {
        return $cuidado;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cuidado $cuidado)
    {
        $cuidado->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cuidado $cuidado)
    {
        $cuidado->ativo = false;
        $cuidado->save();
    }
    public function quantidadecuidados(Empresa $empresa)
    {
        return Cuidado::where('empresa_id', $empresa['id'])->where('ativo', 1)->count();
    }
}
