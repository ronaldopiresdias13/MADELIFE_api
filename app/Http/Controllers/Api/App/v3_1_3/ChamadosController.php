<?php

namespace App\Http\Controllers\Api\App\v3_1_3;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChamadoRequest;
use App\Http\Resources\ChamadoResource;
use App\Models\Chamado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChamadosController extends Controller
{
    public function chamados(Request $request)
    {
        $user = $request->user();
        $pessoa = $user->pessoa;
        $chamados = Chamado::where('finalizado', '=', false)->where('prestador_id', '=', $pessoa->id)->with(['mensagens' => function ($q) {
            $q->with(['atendente', 'prestador'])->orderBy('created_at', 'desc');
        }, 'prestador'])->orderBy('updated_at', 'desc')->get();
        $prestador = $pessoa->prestador()->first();
        $empresas = [];
        if ($prestador != null) {
            $empresas = DB::select(DB::raw('select e.id as id, e.razao as razao from empresas as e join empresa_prestador as ep on ep.empresa_id=e.id and ep.prestador_id=:prestador_id'), ['prestador_id' => $prestador->id]);
        }
        return response()->json(['conversas' => ChamadoResource::collection($chamados), 'empresas' => $empresas]);
    }

    public function criarchamado(ChamadoRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $pessoa = $user->pessoa;
        $chamado = new Chamado();
        $chamado->fill([
            'prestador_id' => $pessoa->id,
            'criador_id' => $pessoa->id,
            'assunto' => $data['assunto'],
            'mensagem_inicial' => $data['mensagem'],
            'finalizado' => false,
            'justificativa' => null,
            'protocolo' => $this->generateRandomString(5),
            'tipo' => $data['area'],
        ]);
        if (isset($data['empresa'])) {
            $chamado->fill(['empresa_id' => $data['empresa']]);
        }
        $chamado->save();

        return response()->json(['chamado' => ChamadoResource::make($chamado)]);
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function enviar_arquivos(ChamadoRequest $request)
    {
        $data = $request->validated();
        Log::info($data);

        Log::info($data['file']);
        Log::info($request->file('file'));

        $files_path = [];
        // foreach ($data['image'] as $arquivo) {

        //     $extension = explode(';', explode('/', explode(':', $arquivo)[1])[1])[0];
        //     $name = 'arquivos_chamado/' . uniqid('foto_') . '.' . $extension;
        //     $image = $arquivo;  // your base64 encoded
        //     $image = str_replace('data:image/' . $extension . ';base64,', '', $image);
        //     $image = str_replace('data:video/' . $extension . ';base64,', '', $image);

        //     $image = base64_decode($image);
        //     Storage::disk('public')->put($name, $image);

        //     array_push($files_path, $name);
        // }
        if ($arquivo = $data['file']) {
            // foreach($arquivos as $arquivo){
            $name = uniqid('foto_') . '.' . $arquivo->getClientOriginalExtension();
            $filename = $arquivo->storeAs('arquivos_chamado', $name, ['disk' => 'public']);
            array_push($files_path, $filename);
            // }
        }
        return response()->json([
            'arquivos' => $files_path
        ]);
    }


    public function get_pendencias()
    {
        $user = request()->user();
        $pessoa = $user->pessoa;
        $pendencia = false;
        $chamados = Chamado::where('prestador_id', $pessoa->id)->whereHas('mensagens', function ($q) {
            $q->where('visto', '=', false)->where('atendente_id', '<>', null);
        })->where('finalizado', '=', false)->with(['mensagens' => function ($q) {
            $q->where('visto', '=', false)->orderBy('created_at', 'desc');
        }])->orderBy('created_at', 'desc')->first();
        if ($chamados != null) {
            $pendencia = true;
        }
        return response()->json([
            'pendencia' => $pendencia,
        ]);
    }
}
