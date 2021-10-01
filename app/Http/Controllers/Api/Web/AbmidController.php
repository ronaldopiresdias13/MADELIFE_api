<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AbmidRequest;
use App\Http\Resources\PlanilhaAbmidEditResource;
use App\Http\Resources\PlanilhaAbmidResource;
use App\Models\DiagnosticoPil;
use App\Models\ItemAbmid;
use App\Models\Paciente;
use App\Models\PlanilhaAbmid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AbmidController extends Controller
{
    public function get_abmids(Request $request){
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $abmids = PlanilhaAbmid::where('empresa_id','=',$empresa_id);
        if($request->inicio!=null && $request->inicio!=''){
            $abmids=$abmids->where('created_at','>=',$request->inicio.' 00:00:00');
        }
        if($request->fim!=null && $request->fim!=''){
            $abmids=$abmids->where('created_at','<=',$request->fim.' 23:59:00');
        }
        if($request->paciente!=null && Str::length($request->paciente)>0){
            $abmids=$abmids->whereHas('paciente',function($q)use($request){
                $q->whereHas('pessoa',function($q2)use($request){
                    $q2->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->paciente.'%']);
                });
            });
        }
        if($request->diagnostico!=null && Str::length($request->diagnostico)>0){
            $abmids=$abmids->whereHas('diagnostico_principal',function($q)use($request){
                $q->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->diagnostico.'%']);
            });
        }
        $abmids=$abmids->orderBy('created_at','desc')->paginate(15, ['*'], 'abmids');
        $current_page_diagnostico = $abmids->currentPage();
        $last_page_diagnostico = $abmids->lastPage();
        $total_diagnostico = $abmids->total();
        $per_page_diagnostico = $abmids->perPage();
        return response()->json([
            'abmids' =>PlanilhaAbmidResource::collection($abmids),
            'current_page_abmid' => $current_page_diagnostico,
            'last_page_abmid' => $last_page_diagnostico,
            'total_abmid' => $total_diagnostico,
            'per_page_abmid' => $per_page_diagnostico,
        ]);
    }

    public function getDadosAbmid(Request $request){
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $pacientes = Paciente::selectRaw('
        pacientes.id as paciente_id, pacientes.pessoa_id as pessoa_paciente_id,p.nome as paciente_nome, 
        pacientes.sexo as paciente_sexo, r.id as responsavel_id, pr.nome as responsavel_nome, r.parentesco,
        r.pessoa_id as pessoa_responsavel_id
        ')->where('pacientes.empresa_id','=',$empresa_id)
        ->join(DB::raw('pessoas as p'),'p.id','=','pacientes.pessoa_id')
        ->join(DB::raw('responsaveis as r'),'r.id','=','pacientes.responsavel_id')
        ->join(DB::raw('pessoas as pr'),'r.pessoa_id','=','pr.id')->get();

        $diagnosticos_principais = DiagnosticoPil::where('flag','=','Primário')->orderBy('nome','asc')->get();

        $diagnosticos_secundarios = DiagnosticoPil::where('flag','=','Secundário')->orderBy('nome','asc')->get();

        // $cuidados = Cuidado::where('ativo','=',1)->where('empresa_id','=',$empresa_id)->orderBy('descricao')->get();

        return response()->json(['pacientes'=>$pacientes,'diagnosticos_principais'=>$diagnosticos_principais,'diagnosticos_secundarios'=>$diagnosticos_secundarios]);
    }

    public function store_abmid(AbmidRequest $request){
        $user = $request->user();
        $data=$request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $abmid = new PlanilhaAbmid();
        $abmid->fill([
            'paciente_id'=>$data['paciente_id'],
            'classificacao'=>$data['classificacao'],
            'diagnostico_principal_id'=>$data['diagnostico_principal_id'],
            'data_avaliacao'=>Carbon::now()->format('Y-m-d H:i:s'),
            'empresa_id'=>$empresa_id
        ])->save();

        $abmid->diagnosticos_secundarios()->Sync($data['diagnostico_secundarios_id']);

        foreach($data['cuidados'] as $g1){
            if($g1['checked']==true){
                $grupo1=new ItemAbmid();
                $grupo1->fill([
                    'abmid_id'=>$abmid->id, 
                    'descricao'=>$g1['label'],
                    'item'=>$g1['value'],
                    'ponto'=>$g1['pontuacao']
                ])->save();
            }
           
        }


        

        return response()->json([
            'abmid'=>$abmid
        ]);
    }

    public function delete_abmid($id)
    {
        $user = request()->user();
        
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $diag = PlanilhaAbmid::where('id','=',$id)->where('empresa_id','=',$empresa_id)->delete();
        return response()->json([
            'status' => true,
        ]);
    }

    public function getAbmidEdit(Request $request,$id){
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $pacientes = Paciente::selectRaw('
        pacientes.id as paciente_id, pacientes.pessoa_id as pessoa_paciente_id,p.nome as paciente_nome, 
        pacientes.sexo as paciente_sexo, r.id as responsavel_id, pr.nome as responsavel_nome, r.parentesco,
        r.pessoa_id as pessoa_responsavel_id
        ')->where('pacientes.empresa_id','=',$empresa_id)
        ->join(DB::raw('pessoas as p'),'p.id','=','pacientes.pessoa_id')
        ->join(DB::raw('responsaveis as r'),'r.id','=','pacientes.responsavel_id')
        ->join(DB::raw('pessoas as pr'),'r.pessoa_id','=','pr.id')->get();

        $diagnosticos_principais = DiagnosticoPil::where('flag','=','Primário')->orderBy('nome','asc')->get();

        $diagnosticos_secundarios = DiagnosticoPil::where('flag','=','Secundário')->orderBy('nome','asc')->get();

        $abmid = PlanilhaAbmid::find($id);
        // $cuidados = Cuidado::where('ativo','=',1)->where('empresa_id','=',$empresa_id)->orderBy('descricao')->get();

        return response()->json([
            'abmid'=>PlanilhaAbmidEditResource::make($abmid),
           'pacientes'=>$pacientes,'diagnosticos_principais'=>$diagnosticos_principais,'diagnosticos_secundarios'=>$diagnosticos_secundarios]);
    }


    public function update_abmid(AbmidRequest $request){
        $user = $request->user();
        $data=$request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $abmid = PlanilhaAbmid::find($data['abmid_id']);
        $abmid->fill([
            'paciente_id'=>$data['paciente_id'],
            'classificacao'=>$data['classificacao'],
            'diagnostico_principal_id'=>$data['diagnostico_principal_id'],
            'data_avaliacao'=>Carbon::now()->format('Y-m-d H:i:s'),
            'empresa_id'=>$empresa_id
        ])->save();

        $abmid->diagnosticos_secundarios()->Sync($data['diagnostico_secundarios_id']);
        $abmid->itens()->delete();

        foreach($data['cuidados'] as $g1){
            if($g1['checked']==true){
                $grupo1=new ItemAbmid();
                $grupo1->fill([
                    'abmid_id'=>$abmid->id, 
                    'descricao'=>$g1['label'],
                    'item'=>$g1['value'],
                    'ponto'=>$g1['pontuacao']
                ])->save();
            }
        }

        return response()->json([
            'abmid'=>$abmid
        ]);
    }
}
