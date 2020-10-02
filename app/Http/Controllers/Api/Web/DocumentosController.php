<?php

namespace App\Http\Controllers\Api\Web;

use App\Documento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $file = $request->file('file');
        if ($file->isValid()) {
            $md5 = md5_file($file);
            $caminho = 'documentos/' . $request['ano'] . '/' . $request['mes'] . '/' . $request['paciente_id'];
            $nome = $md5 . '.' . $file->extension();
            $upload = $file->storeAs($caminho, $nome);
            $nomeOriginal = $file->getClientOriginalName();
            if ($upload) {
                DB::transaction(function () use ($request, $nomeOriginal, $caminho, $nome) {
                    Documento::create(
                        [
                            'paciente_id'  => $request['paciente_id'],
                            'mes'          => $request['mes'],
                            'ano'          => $request['ano'],
                            'nome'         => $nomeOriginal,
                            'caminho'      => $caminho . '/' . $nome,
                            'categoria_id' => $request['categoria_id'],
                            'status'       => $request['status'],
                            'observacao'   => $request['observacao']
                        ]
                    );
                });
                return response()->json('Upload de arquivo bem sucedido!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Erro, Upload não realizado!', 400)->header('Content-Type', 'text/plain');
            }
        } else {
            return response()->json('Arquivo inválido ou corrompido!', 400)->header('Content-Type', 'text/plain');
        }
        // DB::transaction(function () use ($request) {
        //     foreach ($request['documentos'] as $key => $documento) {
        //         $file = $documento->file('file');
        //         if ($file->isValid()) {
        //             $md5 = md5_file($file);
        //             $caminho = 'documentos/' . $request['ano'] . '/' . $request['mes'] . '/' . $request['paciente_id'];
        //             $nome = $md5 . '.' . $file->extension();
        //             $upload = $file->storeAs($caminho, $nome);
        //             $nomeOriginal = $file->getClientOriginalName();
        //             if ($upload) {
        //                 Documento::create(
        //                     [
        //                         'peciente_id'  => $request['paciente_id'],
        //                         'mes'          => $request['mes'],
        //                         'ano'          => $request['ano'],
        //                         'nome'         => $nomeOriginal,
        //                         'caminho'      => $caminho . '/' . $nome,
        //                         'categoria_id' => $documento['categoria_id'],
        //                         'status'       => $documento['status'],
        //                         'observacao'   => $documento['observacao']
        //                     ]
        //                 );
        //                 return response()->json('Upload de arquivo bem sucedido!', 200)->header('Content-Type', 'text/plain');
        //             } else {
        //                 return response()->json('Erro, Upload não realizado!', 400)->header('Content-Type', 'text/plain');
        //             }
        //         } else {
        //             return response()->json('Arquivo inválido ou corrompido!', 400)->header('Content-Type', 'text/plain');
        //         }
        //     }
        // });
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
