<?php

namespace App\Http\Controllers\Api\App\v3_1_4;

use App\Http\Controllers\Controller;
use App\Models\Especialidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EspecialidadesController extends Controller
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
            $user = $request->user();
            $prestador = $user->pessoa->prestador;
            Especialidade::create([
                'descricao' => $request['descricao'],
                'favorito' => false,
                'prestador_id' => $prestador->id
            ]);
        });
        return response()->json([
            'toast' => [
                'text' => 'Especialidade adicionada com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Especialidade  $especialidade
     * @return \Illuminate\Http\Response
     */
    public function show(Especialidade $especialidade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Especialidade  $especialidade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Especialidade $especialidade)
    {
        $especialidade->descricao = $request['descricao'];
        $especialidade->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Especialidade  $especialidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Especialidade $especialidade)
    {
        $especialidade->delete();
    }
}
