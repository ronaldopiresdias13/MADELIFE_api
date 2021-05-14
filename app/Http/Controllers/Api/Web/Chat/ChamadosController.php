<?php

namespace App\Http\Controllers\Api\Web\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChamadoAtendenteRequest;
use App\Http\Requests\ChamadoRequest;
use App\Http\Resources\ChamadoAtendenteResource;
use App\Http\Resources\ChamadoResource;
use App\Http\Resources\OcorrenciaResource;
use App\Models\Chamado;
use App\Models\Ocorrencia;
use App\Models\Pessoa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChamadosController extends Controller
{
    public function chamados_enfermagem(Request $request)
    {
        $user = $request->user();
        $pessoa = $user->pessoa()->first();
        $profissinal = $pessoa->profissional()->first();
        $chamados = Chamado::where('empresa_id','=',$profissinal->empresa_id)->with(['mensagens' => function ($q) {
            $q->with(['atendente', 'prestador'])->orderBy('created_at', 'desc');
        }, 'prestador'])->where('tipo', 'Enfermagem')->where('finalizado','=',0)->where('ocorrencia_id','=',null)->orderBy('updated_at', 'desc')->orderBy('updated_at', 'desc')->get();
        return response()->json(['conversas' => ChamadoAtendenteResource::collection($chamados)]);
    }

    public function chamados_enfermagem_finalizados(Request $request)
    {
        $user = $request->user();
        $pessoa = $user->pessoa()->first();
        $profissinal = $pessoa->profissional()->first();
        $chamados = Chamado::where('empresa_id','=',$profissinal->empresa_id)->with(['mensagens' => function ($q) {
            $q->with(['atendente', 'prestador'])->orderBy('created_at', 'desc');
        }, 'prestador'])->where('tipo', 'Enfermagem')->where('finalizado','=',1)->where('ocorrencia_id','=',null)->orderBy('updated_at', 'desc')->orderBy('updated_at', 'desc')->get();
        return response()->json(['conversas' => ChamadoAtendenteResource::collection($chamados)]);
    }

    public function chamados_ti(Request $request)
    {
        $chamados = Chamado::with(['mensagens' => function ($q) {
            $q->with(['atendente', 'prestador'])->orderBy('created_at', 'desc');
        }, 'prestador'])->where('tipo', 'T.I.')->where('finalizado','=',0)->where('ocorrencia_id','=',null)->orderBy('updated_at', 'desc')->get();
        return response()->json(['conversas' => ChamadoAtendenteResource::collection($chamados)]);
    }

    public function chamados_ti_finalizados(Request $request)
    {
        $chamados = Chamado::with(['mensagens' => function ($q) {
            $q->with(['atendente', 'prestador'])->orderBy('created_at', 'desc');
        }, 'prestador'])->where('tipo', 'T.I.')->where('finalizado','=',1)->where('ocorrencia_id','=',null)->orderBy('updated_at', 'desc')->get();
        return response()->json(['conversas' => ChamadoAtendenteResource::collection($chamados)]);
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


    public function get_pessoas_externo(Request $request)
    {
        $user = $request->user();
        $pessoa = $user->pessoa()->first();
        $profissinal = $pessoa->profissional()->first();
        if ($request->ti == true) {

            $pessoas = Pessoa::has('user')->whereRaw('lower(nome) LIKE lower(?)', ['%' . $request->search . '%'])->orderBy('nome', 'asc')->get();
        } else {
            $pessoas = Pessoa::has('user')->whereHas('prestador', function ($q) use ($profissinal) {
                $q->whereHas('empresas', function ($q2) use ($profissinal) {
                    $q2->where('empresas.id', '=', $profissinal->empresa_id);
                });
            })->whereRaw('lower(nome) LIKE lower(?)', ['%' . $request->search . '%'])->orderBy('nome', 'asc')->get();
        }

        // where('id','<>',$pessoa->id)->
        return response()->json([
            'pessoas' => $pessoas
        ]);
    }

    public function enviarArquivos(ChamadoAtendenteRequest $request)
    {
        $data = $request->validated();
        $files_path = [];
        if ($arquivos = $request->file('arquivos')) {
            foreach ($arquivos as $arquivo) {
                $name = uniqid('arquivo') . '.' . $arquivo->extension();
                $filename = $arquivo->storeAs('arquivos_chat_geral', $name, ['disk' => 'public']);
                array_push($files_path, $filename);
            }
        }

        return response()->json([
            'arquivos' => $files_path
        ]);
    }


    public function criarchamado_atendente_enfermagem(ChamadoAtendenteRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $pessoa = $user->pessoa;
        $profissinal = $pessoa->profissional()->first();
        $ocorrencia=null;
        if(isset($data['ocorrencia'])){
            $ocorrencia=OcorrenciaResource::make(Ocorrencia::find($data['ocorrencia']));
        }
        else{
            $data['ocorrencia']=null;
        }
        $chamado = new Chamado();
        $chamado->fill([
            'prestador_id' => $data['prestador_id'],
            'criador_id' => $pessoa->id,
            'assunto' => $data['assunto'],
            'mensagem_inicial' => $data['mensagem'],
            'finalizado' => false,
            'justificativa' => null,
            'protocolo' => $this->generateRandomString(5),
            'tipo' => 'Enfermagem',
            'empresa_id'=>$profissinal->empresa_id,
            'ocorrencia_id'=>$data['ocorrencia']
        ])->save();

        return response()->json(['chamado' => ChamadoAtendenteResource::make($chamado),'ocorrencia'=>$ocorrencia]);
    }


    public function finalizarchamado_enfermagem(ChamadoAtendenteRequest $request)
    {
        $data = $request->validated();
        $chamado = Chamado::where('id', '=', $data['chamado_id'])->first();
        $chamado->fill([
            'finalizado' => true,
            'justificativa' => $data['justificativa'],

        ])->save();

        return response()->json(['chamado' => ChamadoAtendenteResource::make($chamado)]);
    }


    public function criarchamado_atendente_ti(ChamadoAtendenteRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $pessoa = $user->pessoa;
        $chamado = new Chamado();
        $chamado->fill([
            'prestador_id' => $data['prestador_id'],
            'criador_id' => $pessoa->id,
            'assunto' => $data['assunto'],
            'mensagem_inicial' => $data['mensagem'],
            'finalizado' => false,
            'justificativa' => null,
            'protocolo' => $this->generateRandomString(5),
            'tipo' => 'T.I.'
        ])->save();

        return response()->json(['chamado' => ChamadoAtendenteResource::make($chamado)]);
    }


    public function finalizarchamado_ti(ChamadoAtendenteRequest $request)
    {
        $data = $request->validated();
        $chamado = Chamado::where('id', '=', $data['chamado_id'])->first();
        $chamado->fill([
            'finalizado' => true,
            'justificativa' => $data['justificativa'],

        ])->save();

        return response()->json(['chamado' => ChamadoAtendenteResource::make($chamado)]);
    }







    public function chamados_cliente(Request $request)
    {
        $user = $request->user();
        $pessoa = $user->pessoa;
        $chamados = Chamado::where('finalizado', '=', false)->where('prestador_id', '=', $pessoa->id)->with(['mensagens' => function ($q) {
            $q->with(['atendente', 'prestador'])->orderBy('created_at', 'desc');
        }, 'prestador'])->orderBy('updated_at', 'desc')->get();
        return response()->json(['conversas' => ChamadoResource::collection($chamados)]);
    }

    public function criarchamado_cliente(ChamadoRequest $request)
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
            'tipo' => $data['area']
        ])->save();

        return response()->json(['chamado' => ChamadoResource::make($chamado)]);
    }



    public function enviararquivos_cliente(ChamadoRequest $request)
    {
        $data = $request->validated();
        Log::info($data);

        Log::info($data['image']);
        Log::info($request->file('image'));

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
        if ($arquivo = $data['image']) {
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

    public function get_image($path){
        $pessoa=Pessoa::find($path);
        if (Storage::disk('local')->exists($pessoa->perfil)) {
            $path_image =  Storage::disk('local')->get($pessoa->perfil);
            Log::info(explode(',', $path_image)[1]);
            return base64_decode(explode(',', $path_image)[1]);
        } else {
            return null;
        }
    }
}
