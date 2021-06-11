<?php

namespace App\Http\Controllers\Web\Brasindice;

use App\Http\Controllers\Controller;
use App\Models\Brasindice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrasindiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Brasindice::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        DB::transaction(function () use ($request, $empresa_id) {
            // Brasindice::create($request->all());
            $brasindice = Brasindice::create([
                'empresa_id' => $empresa_id,
                'descricao' => $request['descricao'],
                'versao_revista' => $request['versao_revista'],
                'data' => $request['data'],
                'estado' => $request['estado'],
                'tipo_produto' => $request['tipo_produto'],
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brasindice  $brasindice
     * @return \Illuminate\Http\Response
     */
    public function show(Brasindice $brasindice)
    {
        return $brasindice;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brasindice  $brasindice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brasindice $brasindice)
    {
        DB::transaction(function () use ($request, $brasindice) {
            $brasindice->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brasindice  $brasindice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brasindice $brasindice)
    {
        DB::transaction(function () use ($brasindice) {
            $brasindice->delete();
        });
    }
}
