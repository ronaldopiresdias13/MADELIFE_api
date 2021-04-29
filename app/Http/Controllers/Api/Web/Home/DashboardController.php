<?php

namespace App\Http\Controllers\Api\Web\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\OcorrenciaRequest;
use App\Http\Resources\ChamadoAtendenteResource;
use App\Http\Resources\OcorrenciaResource;
use App\Models\Chamado;
use App\Models\Conversa;
use App\Models\Ocorrencia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function get_dados()
    {
        $user = request()->user();
        $pessoa = $user->pessoa;
        $profissinal = $pessoa->profissional()->first();
        if ($profissinal == null) {

            return response()->json(['chamados_enfermagem' => [], 'chamados_ti' => [],'ocorrencias'=>[]]);
        }
        if ($user->acessos()->where('acessos.nome', '=', 'Área Clínica')->first() != null) {
            $chamados_enfermagem_final = [];
            $chamados_enfermagem = Chamado::where('empresa_id', '=', $profissinal->empresa_id)->has('mensagens')->where('finalizado', '=', false)->where('tipo', '=', 'Enfermagem')->with(['mensagens' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }])->where('ocorrencia_id','=',null)->orderBy('created_at','desc')->get();

            foreach ($chamados_enfermagem as $ti) {
                if ($ti->mensagens[0]->atendente_id == null) {
                    array_push($chamados_enfermagem_final, $ti);
                }
            }
            $chamados_enfermagem = ChamadoAtendenteResource::collection($chamados_enfermagem_final);

            $ocorrencias=Ocorrencia::where('empresa_id', '=', $profissinal->empresa_id)->with(['pessoas','escala','transcricao_produto'=>function($q){
                $q->with('produto','horariomedicamentos');
            }])->where('situacao','=','Pendente')->orderBy('created_at','desc')->get();
        } else {
            $chamados_enfermagem = [];
            $ocorrencias=[];
        }

        if ($user->acessos()->where('acessos.nome', '=', 'TI')->first() != null) {
            $chamados_ti_final = [];
            $chamados_ti = Chamado::has('mensagens')->where('finalizado', '=', false)->where('tipo', '=', 'T.I.')->with(['mensagens' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }])->orderBy('created_at','desc')->get();
            foreach ($chamados_ti as $ti) {
                if ($ti->mensagens[0]->atendente_id == null) {
                    array_push($chamados_ti_final, $ti);
                }
            }
            $chamados_ti = ChamadoAtendenteResource::collection($chamados_ti_final);
        } else {
            $chamados_ti = [];
        }

        

        return response()->json(['chamados_enfermagem' => $chamados_enfermagem, 'chamados_ti' => $chamados_ti,'ocorrencias'=>OcorrenciaResource::collection($ocorrencias)]);
    }


    public function resolver_ocorrencia(OcorrenciaRequest $request)
    {
        $data = $request->validated();
        $ocorrencia=Ocorrencia::find($data['ocorrencia_id']);
        $ocorrencia->fill(['situacao'=>'Resolvida','justificativa'=>$data['justificativa']])->save();
        $ocorrencia->chamados()->update(['finalizado'=>1,'justificativa'=>$data['justificativa']]);
        

        return response()->json(['ocorrencia' => OcorrenciaResource::make($ocorrencia)]);
    }


    public function get_pendencias()
    {
        $user = request()->user();
        $pessoa = $user->pessoa;
        $profissinal = $pessoa->profissional()->first();
        $pendencia_chamado_enfermagem=false;
        $pendencia_chamado_ti=false;
        $pendencia_chamado_home=false;
        $pendencia_chamado=false;
        $pendencia_chat=false;

        if ($profissinal == null) {

            return response()->json([
                'pendencia_chamado_enfermagem' => $pendencia_chamado_enfermagem, 
                'pendencia_chamado_ti' => $pendencia_chamado_ti, 
                'pendencia_chamado_home' => $pendencia_chamado_home, 
                'pendencia_chamado' => $pendencia_chamado, 
                'pendencia_chat' => $pendencia_chat, 
            ]);
        }
        if ($user->acessos()->where('acessos.nome', '=', 'Área Clínica')->first() != null) {
            $chamados_enfermagem = Chamado::where('empresa_id', '=', $profissinal->empresa_id)->whereHas('mensagens',function($q){
                $q->where('visto','=',false)->where('atendente_id','=',null);
            })->where('finalizado', '=', false)->where('tipo', '=', 'Enfermagem')->with(['mensagens' => function ($q) {
                $q->where('visto','=',false)->orderBy('created_at', 'desc');
            }])->where('ocorrencia_id','=',null)->orderBy('created_at','desc')->first();

            if($chamados_enfermagem!=null){
                $pendencia_chamado_enfermagem=true;
            }


            $chamados_enfermagem = Chamado::where('empresa_id', '=', $profissinal->empresa_id)->whereHas('mensagens',function($q){
                $q->where('visto','=',false)->where('atendente_id','=',null);
            })->where('finalizado', '=', false)->where('tipo', '=', 'Enfermagem')->with(['mensagens' => function ($q) {
                $q->where('visto','=',false)->orderBy('created_at', 'desc');
            }])->where('ocorrencia_id','<>',null)->orderBy('created_at','desc')->first();

            if($chamados_enfermagem!=null){
                $pendencia_chamado_home=true;
            }
        } 

        if ($user->acessos()->where('acessos.nome', '=', 'TI')->first() != null) {
            $chamados_ti = Chamado::whereHas('mensagens',function($q){
                $q->where('visto','=',false)->where('atendente_id','=',null);
            })->where('finalizado', '=', false)->where('tipo', '=', 'T.I.')->with(['mensagens' => function ($q) {
                $q->where('visto','=',false)->orderBy('created_at', 'desc');
            }])->orderBy('created_at','desc')->first();
            if($chamados_ti!=null){
                $pendencia_chamado_ti=true;
            }
        }
        $chamados = Chamado::where('prestador_id',$pessoa->id)->whereHas('mensagens',function($q){
            $q->where('visto','=',false)->where('atendente_id','<>',null);
        })->where('finalizado', '=', false)->where('tipo', '=', 'T.I.')->with(['mensagens' => function ($q) {
            $q->where('visto','=',false)->orderBy('created_at', 'desc');
        }])->orderBy('created_at','desc')->first();
        if($chamados!=null){
            $pendencia_chamado=true;
        }

        $chats = Conversa::where(function($q)use($pessoa){
            $q->where('sender_id','=',$pessoa->id)->orWhere('receive_id','=',$pessoa->id);
        })->whereHas('mensagens',function($q)use($pessoa){
            $q->where('sender_id','<>',$pessoa->id)->where('visto','=',false);
        })->first();

        if($chats!=null){
            $pendencia_chat=true;
        }

        return response()->json([
            'pendencia_chamado_enfermagem' => $pendencia_chamado_enfermagem, 
            'pendencia_chamado_ti' => $pendencia_chamado_ti, 
            'pendencia_chamado_home' => $pendencia_chamado_home, 
            'pendencia_chamado' => $pendencia_chamado, 
            'pendencia_chat' => $pendencia_chat, 
        ]);    
    }
}
