<?php

namespace App\Http\Controllers\Api\Web\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChamadoAtendenteRequest;
use App\Http\Requests\ChamadoRequest;
use App\Http\Resources\ChamadoAtendenteResource;
use App\Http\Resources\ChamadoResource;
use App\Models\Chamado;
use App\Models\Pessoa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChamadosController extends Controller
{
    public function chamados_enfermagem(Request $request)
    {
        $chamados = Chamado::with(['mensagens' => function ($q) {
            $q->with(['atendente', 'prestador'])->orderBy('created_at', 'desc');
        }, 'prestador'])->where('tipo','Enfermagem')->where('updated_at','>',Carbon::now()->subDays(5))->orderBy('updated_at', 'desc')->orderBy('updated_at', 'desc')->get();
        return response()->json(['conversas' => ChamadoAtendenteResource::collection($chamados)]);
    }

    public function chamados_ti(Request $request)
    {
        $chamados = Chamado::with(['mensagens' => function ($q) {
            $q->with(['atendente', 'prestador'])->orderBy('created_at', 'desc');
        }, 'prestador'])->where('tipo','T.I.')->where('updated_at','>',Carbon::now()->subDays(5))->orderBy('updated_at', 'desc')->get();
        return response()->json(['conversas' => ChamadoAtendenteResource::collection($chamados)]);
    }
    

    public function generateRandomString($length = 10) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function get_pessoas_externo(Request $request){
        // $user = $request->user();
        // $pessoa=$user->pessoa()->first();
        $pessoas = Pessoa::has('prestador')->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->search.'%'])->with('prestador')->orderBy('nome', 'asc')->get();
        // where('id','<>',$pessoa->id)->
        return response()->json([
            'pessoas'=>$pessoas
        ]);
    }

    public function enviarArquivos(ChamadoAtendenteRequest $request){
        $data=$request->validated();
        $files_path=[];
        if ($arquivos = $request->file('arquivos')) {
            foreach($arquivos as $arquivo){
                $name = uniqid('arquivo') . '.' . $arquivo->extension();
                $filename = $arquivo->storeAs('arquivos_chat_geral', $name, ['disk' => 'public']);
                array_push($files_path,$filename);
            }
        }

        return response()->json([
            'arquivos'=>$files_path
        ]);

    }


    public function criarchamado_atendente_enfermagem(ChamadoAtendenteRequest $request)
    {
        $data=$request->validated();
        $user = $request->user();
        $pessoa=$user->pessoa;
        $chamado=new Chamado();
        $chamado->fill([
            'prestador_id'=>$data['prestador_id'],
            'criador_id'=>$pessoa->id,
            'assunto'=>$data['assunto'],
            'mensagem_inicial'=>$data['mensagem'],
            'finalizado'=>false,
            'justificativa'=>null,
            'protocolo'=>$this->generateRandomString(5),
            'tipo'=>'Enfermagem'
        ])->save();
       
        return response()->json(['chamado' => ChamadoAtendenteResource::make($chamado)]);
    }


    public function finalizarchamado_enfermagem(ChamadoAtendenteRequest $request)
    {
        $data=$request->validated();
        $chamado=Chamado::where('id','=',$data['chamado_id'])->first();
        $chamado->fill([
            'finalizado'=>true,
            'justificativa'=>$data['justificativa'],

        ])->save();
       
        return response()->json(['chamado' => ChamadoAtendenteResource::make($chamado)]);
    }


    public function criarchamado_atendente_ti(ChamadoAtendenteRequest $request)
    {
        $data=$request->validated();
        $user = $request->user();
        $pessoa=$user->pessoa;
        $chamado=new Chamado();
        $chamado->fill([
            'prestador_id'=>$data['prestador_id'],
            'criador_id'=>$pessoa->id,
            'assunto'=>$data['assunto'],
            'mensagem_inicial'=>$data['mensagem'],
            'finalizado'=>false,
            'justificativa'=>null,
            'protocolo'=>$this->generateRandomString(5),
            'tipo'=>'T.I.'
        ])->save();
       
        return response()->json(['chamado' => ChamadoAtendenteResource::make($chamado)]);
    }


    public function finalizarchamado_ti(ChamadoAtendenteRequest $request)
    {
        $data=$request->validated();
        $chamado=Chamado::where('id','=',$data['chamado_id'])->first();
        $chamado->fill([
            'finalizado'=>true,
            'justificativa'=>$data['justificativa'],

        ])->save();
       
        return response()->json(['chamado' => ChamadoAtendenteResource::make($chamado)]);
    }
}
