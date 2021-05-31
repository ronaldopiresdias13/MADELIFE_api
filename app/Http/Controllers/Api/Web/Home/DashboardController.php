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
use Illuminate\Support\Str;


class DashboardController extends Controller
{
    public function get_dados(Request $request)
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
            }])->where('ocorrencia_id','=',null)->orderBy('created_at','desc')->paginate(10, ['*'], 'chamados_enfermagem');

            $current_page_enfermagem = $chamados_enfermagem->currentPage();
            $last_page_enfermagem = $chamados_enfermagem->lastPage();
            $total_enfermagem = $chamados_enfermagem->total();
            $per_page_enfermagem = $chamados_enfermagem->perPage();

            foreach ($chamados_enfermagem as $ti) {
                if ($ti->mensagens[0]->atendente_id == null) {
                    array_push($chamados_enfermagem_final, $ti);
                }
            }
            $chamados_enfermagem = ChamadoAtendenteResource::collection($chamados_enfermagem_final);

            $ocorrencias=Ocorrencia::where('empresa_id', '=', $profissinal->empresa_id)->with(['pessoas','transcricao_produto'=>function($q){
                $q->with('produto','horariomedicamentos');
            }])->where('situacao','=','Pendente');
            if($request->tipo!=null && $request->tipo!='Todos'){
                $ocorrencias=$ocorrencias->where('tipo','=',$request->tipo);
            }
            if($request->paciente!=null && Str::length($request->paciente)>0){
                $ocorrencias=$ocorrencias->whereHas('paciente',function($q)use($request){
                    $q->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->paciente.'%']);
                });
            }
            if($request->responsavel!=null && Str::length($request->responsavel)>0){
                $ocorrencias=$ocorrencias->whereHas('responsavel',function($q)use($request){
                    $q->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->responsavel.'%']);
                });
            }
            if($request->prestador!=null && Str::length($request->prestador)>0){
                $ocorrencias=$ocorrencias->whereHas('pessoas',function($q)use($request){
                    $q->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->prestador.'%']);
                });
            }
            $ocorrencias=$ocorrencias->orderBy('created_at','desc')->paginate(10, ['*'], 'ocorrencias');

            $current_page_ocorrencia = $ocorrencias->currentPage();
            $last_page_ocorrencia = $ocorrencias->lastPage();
            $total_ocorrencia = $ocorrencias->total();
            $per_page_ocorrencia = $ocorrencias->perPage();
        } else {
            $chamados_enfermagem = [];
            $ocorrencias=[];
            $current_page_ocorrencia =1;
            $last_page_ocorrencia = 1;
            $total_ocorrencia = 1;
            $per_page_ocorrencia = 0;

            $current_page_enfermagem = 0;
            $last_page_enfermagem = 0;
            $total_enfermagem = 0;
            $per_page_enfermagem = 0;
        }

        if ($user->acessos()->where('acessos.nome', '=', 'TI')->first() != null) {
            $chamados_ti_final = [];
            $chamados_ti = Chamado::has('mensagens')->where('finalizado', '=', false)->where('tipo', '=', 'T.I.')->with(['mensagens' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }])->orderBy('created_at','desc')->paginate(10, ['*'], 'chamados_ti');

            $current_page_ti = $chamados_ti->currentPage();
            $last_page_ti = $chamados_ti->lastPage();
            $total_ti = $chamados_ti->total();
            $per_page_ti = $chamados_ti->perPage();

            foreach ($chamados_ti as $ti) {
                if ($ti->mensagens[0]->atendente_id == null) {
                    array_push($chamados_ti_final, $ti);
                }
            }
            $chamados_ti = ChamadoAtendenteResource::collection($chamados_ti_final);
        } else {
            $chamados_ti = [];
            $current_page_ti = 0;
            $last_page_ti = 0;
            $total_ti = 0;
            $per_page_ti = 0;
        }

        

        return response()->json([
            'chamados_enfermagem' => $chamados_enfermagem, 
            'chamados_ti' => $chamados_ti,
            'ocorrencias'=>OcorrenciaResource::collection($ocorrencias),
            'current_page_ocorrencia'=>$current_page_ocorrencia,
            'last_page_ocorrencia'=>$last_page_ocorrencia,
            'total_ocorrencia'=>$total_ocorrencia,
            'per_page_ocorrencia'=>$per_page_ocorrencia,

            'current_page_enfermagem'=>$current_page_enfermagem,
            'last_page_enfermagem'=>$last_page_enfermagem,
            'total_enfermagem'=>$total_enfermagem,
            'per_page_enfermagem'=>$per_page_enfermagem,

            'current_page_ti'=>$current_page_ti,
            'last_page_ti'=>$last_page_ti,
            'total_ti'=>$total_ti,
            'per_page_ti'=>$per_page_ti,

        ]);
    }


    public function get_ocorrencias_resolvidas(Request $request)
    {
        $user = request()->user();
        $pessoa = $user->pessoa;
        $profissinal = $pessoa->profissional()->first();
        if ($profissinal == null) {

            return response()->json(['ocorrencias'=>[]]);
        }
        if ($user->acessos()->where('acessos.nome', '=', 'Área Clínica')->first() != null) {

           
            $ocorrencias=Ocorrencia::where('empresa_id', '=', $profissinal->empresa_id)->with(['pessoas','transcricao_produto'=>function($q){
                $q->with('produto','horariomedicamentos');
            }])->where('situacao','=','Resolvida');
            if($request->tipo!=null && $request->tipo!='Todos'){
                $ocorrencias=$ocorrencias->where('tipo','=',$request->tipo);
            }
            if($request->paciente!=null && Str::length($request->paciente)>0){
                $ocorrencias=$ocorrencias->whereHas('paciente',function($q)use($request){
                    $q->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->paciente.'%']);
                });
            }
            if($request->responsavel!=null && Str::length($request->responsavel)>0){
                $ocorrencias=$ocorrencias->whereHas('responsavel',function($q)use($request){
                    $q->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->responsavel.'%']);
                });
            }
            if($request->prestador!=null && Str::length($request->prestador)>0){
                $ocorrencias=$ocorrencias->whereHas('pessoas',function($q)use($request){
                    $q->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->prestador.'%']);
                });
            }
            $ocorrencias=$ocorrencias->orderBy('created_at','desc')->paginate(10, ['*'], 'ocorrencias');


            $current_page_ocorrencia = $ocorrencias->currentPage();
            $last_page_ocorrencia = $ocorrencias->lastPage();
            $total_ocorrencia = $ocorrencias->total();
            $per_page_ocorrencia = $ocorrencias->perPage();
        } else {
            $chamados_enfermagem = [];
            $ocorrencias=[];
            $current_page_ocorrencia =1;
            $last_page_ocorrencia = 1;
            $total_ocorrencia = 1;
            $per_page_ocorrencia = 0;

            
        }

        

        

        return response()->json([
            'ocorrencias'=>OcorrenciaResource::collection($ocorrencias),
            'current_page_ocorrencia'=>$current_page_ocorrencia,
            'last_page_ocorrencia'=>$last_page_ocorrencia,
            'total_ocorrencia'=>$total_ocorrencia,
            'per_page_ocorrencia'=>$per_page_ocorrencia,


        ]);
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
