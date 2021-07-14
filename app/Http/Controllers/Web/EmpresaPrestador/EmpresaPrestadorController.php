<?php

namespace App\Http\Controllers\Web\EmpresaPrestador;

use App\Http\Controllers\Controller;
use App\Models\EmpresaPrestador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresaPrestadorController extends Controller
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
        $file = $request->file('file');
        $request = json_decode($request->data, true);
        if ($file && $file->isValid()) {
            $md5 = md5_file($file);
            $caminho = 'contratosPrestadores/' . $request['prestador_id'];
            $nome = $md5 . '.' . $file->extension();
            $upload = $file->storeAs($caminho, $nome);
            $nomeOriginal = $file->getClientOriginalName();
            if ($upload) {
                DB::transaction(function () use ($request, $caminho, $nome, $nomeOriginal) {
                    $empresa_prestador = EmpresaPrestador::create([
                        'empresa_id'   => $request['empresa_id'],
                        'prestador_id' => $request['prestador_id'],
                        'contrato'     => $caminho . '/' . $nome,
                        'nome'         => $nomeOriginal,
                        'dataInicio'   => $request['dataInicio'],
                        'dataFim'      => $request['dataFim'],
                        'INSS'         => $request['INSS'],
                        'ISS'          => $request['ISS'],
                        'taxaADM'      => $request['taxaADM'],
                        'adicionalExtra' => $request['adicionalExtra'],
                        'adicionalOutros' => $request['adicionalOutros'],
                        'status'       => $request['status'],
                        'forma_contratacao' => $request['forma_contratacao'],
                    ]);
                });
                return response()->json('Upload de arquivo bem sucedido!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Erro, Upload não realizado!', 400)->header('Content-Type', 'text/plain');
            }
        } else {
            if ($file == null) {
                DB::transaction(function () use ($request) {
                    $empresa_prestador = EmpresaPrestador::create([
                        'empresa_id'   => $request['empresa_id'],
                        'prestador_id' => $request['prestador_id'],
                        'contrato'     => '',
                        'nome'         => '',
                        'dataInicio'   => $request['dataInicio'],
                        'dataFim'      => $request['dataFim'],
                        'INSS'         => $request['INSS'],
                        'ISS'          => $request['ISS'],
                        'taxaADM'      => $request['taxaADM'],
                        'adicionalExtra' => $request['adicionalExtra'],
                        'adicionalOutros' => $request['adicionalOutros'],
                        'status'       => $request['status'],
                        'forma_contratacao' => $request['forma_contratacao'],
                    ]);
                });
                return response()->json('Dados salvos com sucesso!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Arquivo inválido ou corrompido!', 400)->header('Content-Type', 'text/plain');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmpresaPrestador  $empresaPrestador
     * @return \Illuminate\Http\Response
     */
    public function show(EmpresaPrestador $empresaPrestador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmpresaPrestador  $empresaPrestador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpresaPrestador $empresaPrestador)
    {
        $file = $request->file('file');
        $request = json_decode($request->data, true);
        if ($file && $file->isValid()) {
            $md5 = md5_file($file);
            $caminho = 'contratosPrestadores/' . $request['prestador_id'];
            $nome = $md5 . '.' . $file->extension();
            $upload = $file->storeAs($caminho, $nome);
            $nomeOriginal = $file->getClientOriginalName();
            if ($upload) {
                DB::transaction(function () use ($request, $caminho, $nome, $nomeOriginal, $empresaPrestador) {
                    $empresaPrestador->contrato   = $caminho . '/' . $nome;
                    $empresaPrestador->nome       = $nomeOriginal;
                    $empresaPrestador->dataInicio = $request['dataInicio'];
                    $empresaPrestador->dataFim    = $request['dataFim'];
                    $empresaPrestador->INSS       = $request['INSS'];
                    $empresaPrestador->ISS        = $request['ISS'];
                    $empresaPrestador->taxaADM    = $request['taxaADM'];
                    $empresaPrestador->adicionalExtra = $request['adicionalExtra'];
                    $empresaPrestador->adicionalOutros = $request['adicionalOutros'];
                    $empresaPrestador->status     = $request['status'];
                    $empresaPrestador->forma_contratacao  = $request['forma_contratacao'];
                    $empresaPrestador->save();
                });
                return response()->json('Upload de arquivo bem sucedido!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Erro, Upload não realizado!', 400)->header('Content-Type', 'text/plain');
            }
        } else {
            if ($file == null) {
                DB::transaction(function () use ($request, $empresaPrestador) {
                    $empresaPrestador->contrato   = $request['contrato'];
                    $empresaPrestador->nome       = $request['nome'];
                    $empresaPrestador->dataInicio = $request['dataInicio'];
                    $empresaPrestador->dataFim    = $request['dataFim'];
                    $empresaPrestador->status     = $request['status'];
                    $empresaPrestador->forma_contratacao  = $request['forma_contratacao'];
                    $empresaPrestador->save();
                });
                return response()->json('Dados salvos com sucesso!', 200)->header('Content-Type', 'text/plain');
            } else {
                return response()->json('Arquivo inválido ou corrompido!', 400)->header('Content-Type', 'text/plain');
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmpresaPrestador  $empresaPrestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmpresaPrestador $empresaPrestador)
    {
        //
    }
}
