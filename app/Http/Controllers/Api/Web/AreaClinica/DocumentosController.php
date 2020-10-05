<?php

namespace App\Http\Controllers\Api\Web\AreaClinica;

use App\Documento;
use App\Http\Controllers\Controller;
use App\Paciente;
use Illuminate\Http\Request;

class DocumentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listDocumentos(Request $request)
    {
        $hoje = getdate();

        $pacientes = Paciente::with([
            'pessoa',
            'documentos' => function ($query) use ($request, $hoje) {
                $query->where('mes', $request->mes ? $request->mes : $hoje['mon'])
                    ->where('ano', $request->ano ? $request->ano : $hoje['year']);
            }
        ])
            ->where('ativo', true)
            ->where('id', 'like', $request->paciente_id ? $request->paciente_id : '%')
            ->get();

        return $pacientes;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function show(Documento $documento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Documento $documento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Documento $documento)
    {
        //
    }
}
