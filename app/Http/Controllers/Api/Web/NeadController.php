<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\NeadRequest;
use App\Http\Resources\NeadEditResource;
use App\Http\Resources\NeadResource;
use App\Models\ClientPatient;
use App\Models\DiagnosticoPil;
use App\Models\Nead;
use App\Models\NeadGrupo1;
use App\Models\NeadGrupo2;
use App\Models\NeadGrupo3;
use App\Models\NeadKatz;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NeadController extends Controller
{
    public function get_neads(Request $request){
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $pils = Nead::where('empresa_id','=',$empresa_id);
        if($request->inicio!=null && $request->inicio!=''){
            $pils=$pils->where('created_at','>=',$request->inicio.' 00:00:00');
        }
        if($request->fim!=null && $request->fim!=''){
            $pils=$pils->where('created_at','<=',$request->fim.' 23:59:00');
        }
        if($request->paciente!=null && Str::length($request->paciente)>0){
            $pils = $pils->where(function($q3)use ($request){
                $q3->where(function($q4)use ($request){
                    $q4->whereHas('paciente', function ($q) use ($request) {
                        $q->whereHas('pessoa', function ($q2) use ($request) {
                            $q2->whereRaw('lower(nome) LIKE lower(?)', ['%' . $request->paciente . '%']);
                        });
                    });
                })->orWhere(function($q5)use ($request){
                    $q5->whereHas('cpaciente', function ($q) use ($request) {
                        $q->whereRaw('lower(nome) LIKE lower(?)', ['%' . $request->paciente . '%']);
                    });
                });
            });
        }
        if($request->diagnostico!=null && Str::length($request->diagnostico)>0){
            $pils=$pils->whereHas('diagnosticos_principais',function($q)use($request){
                $q->whereRaw('lower(nome) LIKE lower(?)',['%'.$request->diagnostico.'%']);
            });
        }
        $pils=$pils->orderBy('created_at','desc')->paginate(15, ['*'], 'neads');
        $current_page_diagnostico = $pils->currentPage();
        $last_page_diagnostico = $pils->lastPage();
        $total_diagnostico = $pils->total();
        $per_page_diagnostico = $pils->perPage();
        return response()->json([
            'neads' =>NeadResource::collection($pils),
            'current_page_nead' => $current_page_diagnostico,
            'last_page_nead' => $last_page_diagnostico,
            'total_nead' => $total_diagnostico,
            'per_page_nead' => $per_page_diagnostico,
        ]);
    }

    public function getDadosNead(Request $request){
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $pacientes = Paciente::selectRaw('pacientes.id as id,pacientes.pessoa_id as pessoa_id,
        pacientes.id as paciente_id, pacientes.pessoa_id as pessoa_paciente_id,p.nome as paciente_nome, 
        pacientes.sexo as paciente_sexo, r.id as responsavel_id, pr.nome as responsavel_nome, r.parentesco,
        r.pessoa_id as pessoa_responsavel_id
        ')->where('pacientes.empresa_id','=',$empresa_id)
        ->join(DB::raw('pessoas as p'),'p.id','=','pacientes.pessoa_id')
        ->join(DB::raw('responsaveis as r'),'r.id','=','pacientes.responsavel_id')
        ->join(DB::raw('pessoas as pr'),'r.pessoa_id','=','pr.id')->with(['pessoa.enderecos.cidade','responsavel.pessoa.telefones'])->orderBy('pr.nome')->get();

        $clients_patients = ClientPatient::where('empresa_id', '=', $empresa_id)->orderBy('nome')->get();

        $diagnosticos_principais = DiagnosticoPil::where('flag','=','Primário')->orderBy('nome','asc')->get();

        $diagnosticos_secundarios = DiagnosticoPil::where('flag','=','Secundário')->orderBy('nome','asc')->get();

        // $cuidados = Cuidado::where('ativo','=',1)->where('empresa_id','=',$empresa_id)->orderBy('descricao')->get();

        return response()->json(['medicamentos'=>[], 'cuidados'=>[], 'pacientes'=>$pacientes,'diagnosticos_principais'=>$diagnosticos_principais,'diagnosticos_secundarios'=>$diagnosticos_secundarios,'clients_patients'=>$clients_patients]);
    }

    public function store_nead(NeadRequest $request){
        $user = $request->user();
        $data=$request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        if(isset($data['paciente']['paciente_id'])){

            $nead_check = Nead::where('empresa_id','=',$empresa_id)->where('paciente_id','=',$data['paciente']['paciente_id'])->first();
            if($nead_check!=null){
                return response()->json(['status'=>false, 'message'=>'Esse paciente já possui uma Nead cadastrada']);
            }
            $nead = new Nead();
            $nead->fill([
                'paciente_id'=>$data['paciente']['paciente_id'],
                'cpatient_id'=>null,
                'observacao_grupo1'=>isset($data['observacao_grupo1'])?$data['observacao_grupo1']:null,

                'pontuacao_final'=>$data['classificacao_pacient']['pontos'],
                'pontuacao_katz'=>$data['classificacao_katz']['pontos'],
                'diagnostico_principal_id'=>$data['diagnosticos_principais'][0]['id'],
                'data_avaliacao'=>Carbon::now()->format('Y-m-d H:i:s'),
                'classificacaop_selecionado'=>isset($data['classificacao_pacient']['selecionado'])?$data['classificacao_pacient']['selecionado']:null,
                'empresa_id'=>$empresa_id
            ])->save();
        }
        else{
            $nead_check = Nead::where('empresa_id','=',$empresa_id)->where('cpatient_id','=',$data['paciente']['id'])->first();
            if($nead_check!=null){
                return response()->json(['status'=>false, 'message'=>'Esse paciente já possui uma Nead cadastrada']);
            }
            $nead = new Nead();
            $nead->fill([
                'cpatient_id'=>$data['paciente']['id'],
                'paciente_id'=>null,
                'observacao_grupo1'=>isset($data['observacao_grupo1'])?$data['observacao_grupo1']:null,

                'pontuacao_final'=>$data['classificacao_pacient']['pontos'],
                'pontuacao_katz'=>$data['classificacao_katz']['pontos'],
                'diagnostico_principal_id'=>$data['diagnosticos_principais'][0]['id'],
                'data_avaliacao'=>Carbon::now()->format('Y-m-d H:i:s'),
                'classificacaop_selecionado'=>isset($data['classificacao_pacient']['selecionado'])?$data['classificacao_pacient']['selecionado']:null,
                'empresa_id'=>$empresa_id
            ])->save();
        }

        $nead->diagnosticos_secundarios()->Sync($data['diagnostico_secundarios_id']);

        $diagnosticos_principais = [];
        foreach ($data['diagnosticos_principais'] as $diag_principal) {
            array_push($diagnosticos_principais, $diag_principal['id']);
        }
        $nead->diagnosticos_principais()->Sync($diagnosticos_principais);


        foreach($data['grupo1'] as $g1){
            $grupo1=new NeadGrupo1();
            $grupo1->fill([
                'neads_id'=>$nead->id, 
                'categoria'=>$g1['label'],
                'value'=>$g1['value']
            ])->save();
        }


        foreach($data['grupo2'] as $g2){
            $grupo1=new NeadGrupo2();
            $grupo1->fill([
                'neads_id'=>$nead->id, 
                'categoria'=>$g2['label'],
                'value'=>$g2['value']
            ])->save();
        }

        foreach($data['grupo3'] as $g3){
            $grupo1=new NeadGrupo3();
            $grupo1->fill([
                'neads_id'=>$nead->id, 
                'categoria'=>$g3['label'],
                'value'=>$g3['value'],
                'pontuacao'=>$g3['pontuacao_value']
            ])->save();
        }

        foreach($data['escore_katz'] as $gk){
            $grupo1=new NeadKatz();
            $grupo1->fill([
                'neads_id'=>$nead->id, 
                'categoria'=>$gk['label'],
                'value'=>$gk['value'],
                'pontuacao'=>$gk['pontuacao_value']
            ])->save();
        }

        return response()->json([
            'nead'=>$nead
        ]);
    }

    public function delete_nead($id)
    {
        $user = request()->user();
        
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $diag = Nead::where('id','=',$id)->where('empresa_id','=',$empresa_id)->delete();
        return response()->json([
            'status' => true,
        ]);
    }

    public function getNeadEdit(Request $request,$id){
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $pacientes = Paciente::selectRaw('pacientes.id as id,pacientes.pessoa_id as pessoa_id,
            pacientes.id as paciente_id, pacientes.pessoa_id as pessoa_paciente_id,p.nome as paciente_nome, 
            pacientes.sexo as paciente_sexo, r.id as responsavel_id, pr.nome as responsavel_nome, r.parentesco,
            r.pessoa_id as pessoa_responsavel_id
            ')->where('pacientes.empresa_id','=',$empresa_id)
            ->join(DB::raw('pessoas as p'),'p.id','=','pacientes.pessoa_id')
            ->join(DB::raw('responsaveis as r'),'r.id','=','pacientes.responsavel_id')
            ->join(DB::raw('pessoas as pr'),'r.pessoa_id','=','pr.id')->with(['pessoa.enderecos.cidade','responsavel.pessoa.telefones'])->orderBy('pr.nome')->get();
    
        $clients_patients = ClientPatient::where('empresa_id', '=', $empresa_id)->get();

        $diagnosticos_principais = DiagnosticoPil::where('flag','=','Primário')->orderBy('nome','asc')->get();

        $diagnosticos_secundarios = DiagnosticoPil::where('flag','=','Secundário')->orderBy('nome','asc')->get();

        $nead = Nead::find($id);
        // $cuidados = Cuidado::where('ativo','=',1)->where('empresa_id','=',$empresa_id)->orderBy('descricao')->get();

        return response()->json([
            'nead'=>NeadEditResource::make($nead),
            'clients_patients'=>$clients_patients,
            'medicamentos'=>[], 'cuidados'=>[], 'pacientes'=>$pacientes,'diagnosticos_principais'=>$diagnosticos_principais,'diagnosticos_secundarios'=>$diagnosticos_secundarios]);
    }


    public function update_nead(NeadRequest $request){
        $user = $request->user();
        $data=$request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $nead = Nead::find($data['nead_id']);
        if(isset($data['paciente']['paciente_id'])){

            $nead_check = Nead::where('empresa_id','=',$empresa_id)->where('paciente_id','=',$data['paciente']['paciente_id'])->first();
            if($nead_check!=null && $nead_check->id!=$nead->id){
                return response()->json(['status'=>false, 'message'=>'Esse paciente já possui uma Nead cadastrada']);
            }
            $nead->fill([
                'paciente_id'=>$data['paciente']['paciente_id'],
                'cpatient_id'=>null,
                'observacao_grupo1'=>isset($data['observacao_grupo1'])?$data['observacao_grupo1']:null,
                'pontuacao_final'=>$data['classificacao_pacient']['pontos'],
                'pontuacao_katz'=>$data['classificacao_katz']['pontos'],
                'diagnostico_principal_id'=>$data['diagnosticos_principais'][0]['id'],
                'data_avaliacao'=>Carbon::now()->format('Y-m-d H:i:s'),
                'classificacaop_selecionado'=>isset($data['classificacao_pacient']['selecionado'])?$data['classificacao_pacient']['selecionado']:null,

                'empresa_id'=>$empresa_id
            ])->save();
        }
        else{
            $nead_check = Nead::where('empresa_id','=',$empresa_id)->where('cpatient_id','=',$data['paciente']['id'])->first();
            if($nead_check!=null && $nead_check->id!=$nead->id){
                return response()->json(['status'=>false, 'message'=>'Esse paciente já possui uma Nead cadastrada']);
            }
            $nead->fill([
                'cpatient_id'=>$data['paciente']['id'],
                'paciente_id'=>null,
                'observacao_grupo1'=>isset($data['observacao_grupo1'])?$data['observacao_grupo1']:null,

                'pontuacao_final'=>$data['classificacao_pacient']['pontos'],
                'pontuacao_katz'=>$data['classificacao_katz']['pontos'],
                'diagnostico_principal_id'=>$data['diagnosticos_principais'][0]['id'],
                'data_avaliacao'=>Carbon::now()->format('Y-m-d H:i:s'),
                'classificacaop_selecionado'=>isset($data['classificacao_pacient']['selecionado'])?$data['classificacao_pacient']['selecionado']:null,

                'empresa_id'=>$empresa_id
            ])->save();
        }

        $nead->diagnosticos_secundarios()->Sync($data['diagnostico_secundarios_id']);


        $diagnosticos_principais = [];
        foreach ($data['diagnosticos_principais'] as $diag_principal) {
            array_push($diagnosticos_principais, $diag_principal['id']);
        }
        $nead->diagnosticos_principais()->Sync($diagnosticos_principais);


        $nead->grupos1()->delete();
        $nead->grupos2()->delete();
        $nead->grupos3()->delete();
        $nead->katz()->delete();


        foreach($data['grupo1'] as $g1){
            $grupo1=new NeadGrupo1();
            $grupo1->fill([
                'neads_id'=>$nead->id, 
                'categoria'=>$g1['label'],
                'value'=>$g1['value']
            ])->save();
        }


        foreach($data['grupo2'] as $g2){
            $grupo1=new NeadGrupo2();
            $grupo1->fill([
                'neads_id'=>$nead->id, 
                'categoria'=>$g2['label'],
                'value'=>$g2['value']
            ])->save();
        }

        foreach($data['grupo3'] as $g3){
            $grupo1=new NeadGrupo3();
            $grupo1->fill([
                'neads_id'=>$nead->id, 
                'categoria'=>$g3['label'],
                'value'=>$g3['value'],
                'pontuacao'=>$g3['pontuacao_value']
            ])->save();
        }

        foreach($data['escore_katz'] as $gk){
            $grupo1=new NeadKatz();
            $grupo1->fill([
                'neads_id'=>$nead->id, 
                'categoria'=>$gk['label'],
                'value'=>$gk['value'],
                'pontuacao'=>$gk['pontuacao_value']
            ])->save();
        }

        return response()->json([
            'nead'=>$nead
        ]);
    }
}
