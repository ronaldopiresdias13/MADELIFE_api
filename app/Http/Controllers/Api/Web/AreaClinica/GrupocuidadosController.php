<?php

namespace App\Http\Controllers\Api\Web\AreaClinica;

use App\Models\Grupocuidado;
use App\Models\Cuidado;
use App\Models\CuidadoGrupocuidado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrupocuidadosController extends Controller
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
        return Grupocuidado::with(['cuidados'])
            ->where('empresa_id', $empresa_id)
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
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $empresa_id = $user->pessoa->profissional->empresa->id;
            $total = Grupocuidado::where('empresa_id', $request->empresa_id)->count();
            $grupocuidado = Grupocuidado::create([
                'descricao' => $request['descricao'],
                'codigo' => $total + 1,
                'empresa_id' => $empresa_id,
                'status' => $request['status'],
            ]);
            foreach ($request['cuidado'] as $key => $cuidado) {
                $cuidado = CuidadoGrupocuidado::firstOrCreate([
                    'grupocuidado_id' => $grupocuidado->id,
                    'cuidado_id'    => $cuidado['id']
                ]);
            }
        });
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Grupocuidado  $grupocuidado
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Grupocuidado $grupocuidado)
    {
        $grupocuidado->cuidados;
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
        $grupocuidado->descricao = $request['descricao'];
        $grupocuidado->save();
        foreach ($request['cuidado'] as $key => $cuidado) {
            $cuidado = CuidadoGrupocuidado::updateOrCreate([
                'grupocuidado_id' => $grupocuidado->id,
                'cuidado_id'    => $cuidado['id']
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Grupocuidado  $grupocuidado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grupocuidado $grupocuidado)
    {
        $grupocuidado->ativo = false;
        $grupocuidado->save();
    }
}
