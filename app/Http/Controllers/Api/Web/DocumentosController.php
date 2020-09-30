<?php

namespace App\Http\Controllers\Api\Web;

use App\Documento;
use App\Http\Controllers\Controller;
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

        $documentos = Documento::where('ativo', true)
            ->where('paciente_id', 'like', $request->paciente_id ? $request->paciente_id : '%')
            ->where('mes', $request->mes ? $request->mes : $hoje['mon'])
            ->where('ano', $request->ano ? $request->ano : $hoje['year'])
            ->get();

        return $documentos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newDocumento(Request $request)
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
