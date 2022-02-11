<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnexoBRequest;
use App\Http\Resources\AnexoBEditResource;
use App\Http\Resources\AnexoBResource;
use App\Models\AnexoBInformacoes;
use App\Models\ClientPatient;
use App\Models\OpcoesAnexoB;
use App\Models\Paciente;
use App\Models\PlanilhaAnexoB;
use App\Models\ServicoSocioAmbiental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class AnexoBController extends Controller
{
    public function get_anexosB(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $anexoas = PlanilhaAnexoB::where('empresa_id', '=', $empresa_id);
        if ($request->inicio != null && $request->inicio != '') {
            $anexoas = $anexoas->where('created_at', '>=', $request->inicio . ' 00:00:00');
        }
        if ($request->fim != null && $request->fim != '') {
            $anexoas = $anexoas->where('created_at', '<=', $request->fim . ' 23:59:00');
        }
        if ($request->paciente != null && Str::length($request->paciente) > 0) {
            $anexoas = $anexoas->where(function($q3)use ($request){
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
       
        $anexoas = $anexoas->orderBy('created_at', 'desc')->paginate(15, ['*'], 'anexoas');
        $current_page_diagnostico = $anexoas->currentPage();
        $last_page_diagnostico = $anexoas->lastPage();
        $total_diagnostico = $anexoas->total();
        $per_page_diagnostico = $anexoas->perPage();

       
        return response()->json([
            'anexos' => AnexoBResource::collection($anexoas),
            'current_page_anexoA' => $current_page_diagnostico,
            'last_page_anexoA' => $last_page_diagnostico,
            'total_anexoA' => $total_diagnostico,
            'per_page_anexoA' => $per_page_diagnostico,
            
        ]);
    }

    public function getDadosAnexoB(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $pacientes = Paciente::selectRaw('pacientes.id as id,pacientes.pessoa_id as pessoa_id,
        pacientes.id as paciente_id, pacientes.pessoa_id as pessoa_paciente_id,p.nome as paciente_nome, 
        pacientes.sexo as paciente_sexo, r.id as responsavel_id, pr.nome as responsavel_nome, r.parentesco,
        r.pessoa_id as pessoa_responsavel_id
        ')->where('pacientes.empresa_id', '=', $empresa_id)
            ->join(DB::raw('pessoas as p'), 'p.id', '=', 'pacientes.pessoa_id')
            ->join(DB::raw('responsaveis as r'), 'r.id', '=', 'pacientes.responsavel_id')
            ->join(DB::raw('pessoas as pr'), 'r.pessoa_id', '=', 'pr.id')->with(['pessoa.enderecos.cidade','responsavel.pessoa.telefones'])->orderBy('pr.nome')->get();

        $clients_patients = ClientPatient::where('empresa_id', '=', $empresa_id)->orderBy('nome')->get();
        $servicos_hospital = ServicoSocioAmbiental::where('empresa_id','=',$empresa_id)->where('tipo','=','hospital')->orderBy('nome','asc')->get();
        $servicos_laboratorio = ServicoSocioAmbiental::where('empresa_id','=',$empresa_id)->where('tipo','=','laboratorio')->orderBy('nome','asc')->get();
        $servicos_resgate = ServicoSocioAmbiental::where('empresa_id','=',$empresa_id)->where('tipo','=','resgate')->orderBy('nome','asc')->get();

        return response()->json([
            'clients_patients'=>$clients_patients,'pacientes' => $pacientes,
            'servicos_hospital'=>$servicos_hospital,
            'servicos_laboratorio'=>$servicos_laboratorio,
            'servicos_resgate'=>$servicos_resgate,
        
        ]);
    }

    public function store_anexob(AnexoBRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        if(isset($data['paciente']['paciente_id'])){

            $nead_check = PlanilhaAnexoB::where('empresa_id','=',$empresa_id)->where('paciente_id','=',$data['paciente']['paciente_id'])->first();
            if($nead_check!=null){
                return response()->json(['status'=>false, 'message'=>'Esse paciente já possui um Anexo B cadastrado']);
            }

            $anexoa = new PlanilhaAnexoB();
            $anexoa->fill([
                'empresa_id' => $empresa_id,
                'paciente_id'=>$data['paciente']['paciente_id'],
                'cpatient_id'=>null,
                'data_avaliacao' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->save();
        }
        else{
            $nead_check = PlanilhaAnexoB::where('empresa_id','=',$empresa_id)->where('cpatient_id','=',$data['paciente']['id'])->first();
            if($nead_check!=null){
                return response()->json(['status'=>false, 'message'=>'Esse paciente já possui um Anexo B cadastrado']);
            }

            $anexoa = new PlanilhaAnexoB();
            $anexoa->fill([
                'empresa_id' => $empresa_id,
                'cpatient_id'=>$data['paciente']['id'],
                'paciente_id'=>null,
                'data_avaliacao' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->save();
        }

        foreach ($data['dados'] as $key => $g1) {
            if(isset($g1['descricao2'])){
                if($g1['descricao2']=='null' || $g1['descricao2']=='undefined'){
                    $g1['descricao2']=null;
                }
            }
            if(isset($g1['value_real'])){
                if($g1['value_real']=='null' || $g1['value_real']=='undefined'){
                    $g1['value_real']=null;
                }
            }
            if(isset($g1['value'])){
                if($g1['value']=='null' || $g1['value']=='undefined'){
                    $g1['value']=null;
                }
            }
            if(isset($g1['descricao'])){
                if($g1['descricao']=='null' || $g1['descricao']=='undefined'){
                    $g1['descricao']=null;
                }
            }
            $grupo1 = new OpcoesAnexoB();
            $grupo1->fill([
                'anexo_b_id'=>$anexoa->id,
                'categoria'=>$key,
                'descricao_value_2'=>(!isset($g1['descricao2']) || $g1['descricao2']=='')?null:$g1['descricao2'],
                'value_real'=>(!isset($g1['value_real']) || $g1['value_real']=='')?null:$g1['value_real'],

                'value'=>(!isset($g1['value']) || $g1['value']=='')?null:$g1['value'],

                'descricao_value'=>(!isset($g1['descricao']) || $g1['descricao']=='')?null:$g1['descricao'],
            ])->save();
        }

        // foreach ($data['informacoes_complementares'] as $key => $g1) {
        //     $grupo1 = new AnexoBInformacoes();
        //     $grupo1->fill([
        //         'anexo_b_id'=>$anexoa->id, 
        //         'categoria'=>$g1['label'],
        //         'value'=>$g1['value'],
        //         'telefone'=>$g1['telefone'],
        //         'cep'=>isset($g1['cep'])?$g1['cep']:null,
        //         'rua'=>isset($g1['rua'])?$g1['rua']:null,
        //         'numero'=>isset($g1['numero'])?$g1['numero']:null,
        //         'bairro'=>isset($g1['bairro'])?$g1['bairro']:null,
        //         'cidade'=>isset($g1['cidade'])?$g1['cidade']:null,
        //         'estado'=>isset($g1['estado'])?$g1['estado']:null,
        //         'complemento'=>isset($g1['complemento'])?$g1['complemento']:null,
        //     ])->save();
        // }
        if(isset($data['servicos_selecionados'])){
            $anexoa->servicos()->Sync($data['servicos_selecionados']);
        }
        else{
            $anexoa->servicos()->Sync([]);
        }


        return response()->json([
            'anexob' => $anexoa
        ]);
    }

    public function delete_anexob($id)
    {
        $user = request()->user();

        $empresa_id = $user->pessoa->profissional->empresa_id;
        $diag = PlanilhaAnexoB::where('id','=',$id)->where('empresa_id','=',$empresa_id)->delete();
        return response()->json([
            'status' => true,
        ]);
    }

    public function getAnexoBEdit(Request $request,$id){
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

        $anexoa = PlanilhaAnexoB::find($id);
        // $cuidados = Cuidado::where('ativo','=',1)->where('empresa_id','=',$empresa_id)->orderBy('descricao')->get();
        $servicos_hospital = ServicoSocioAmbiental::where('empresa_id','=',$empresa_id)->where('tipo','=','hospital')->orderBy('nome','asc')->get();
        $servicos_laboratorio = ServicoSocioAmbiental::where('empresa_id','=',$empresa_id)->where('tipo','=','laboratorio')->orderBy('nome','asc')->get();
        $servicos_resgate = ServicoSocioAmbiental::where('empresa_id','=',$empresa_id)->where('tipo','=','resgate')->orderBy('nome','asc')->get();

        return response()->json([
            'anexob'=>AnexoBEditResource::make($anexoa),
            'clients_patients'=>$clients_patients,
            'servicos_hospital'=>$servicos_hospital,
            'servicos_laboratorio'=>$servicos_laboratorio,
            'servicos_resgate'=>$servicos_resgate,
           'pacientes'=>$pacientes]);
    }


    public function update_anexob(AnexoBRequest $request){
        $user = $request->user();
        $data = $request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $anexoa = PlanilhaAnexoB::find($data['anexo_b_id']);

        if(isset($data['paciente']['paciente_id'])){

            $nead_check = PlanilhaAnexoB::where('empresa_id','=',$empresa_id)->where('paciente_id','=',$data['paciente']['paciente_id'])->first();
            if($nead_check!=null && $nead_check->id!=$anexoa->id){
                return response()->json(['status'=>false, 'message'=>'Esse paciente já possui um Anexo B cadastrado']);
            }

            $anexoa->fill([
                'empresa_id' => $empresa_id,
                'paciente_id'=>$data['paciente']['paciente_id'],
                'cpatient_id'=>null,
                'data_avaliacao' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->save();
        }
        else{
            $nead_check = PlanilhaAnexoB::where('empresa_id','=',$empresa_id)->where('cpatient_id','=',$data['paciente']['id'])->first();
            if($nead_check!=null && $nead_check->id!=$anexoa->id){
                return response()->json(['status'=>false, 'message'=>'Esse paciente já possui um Anexo B cadastrado']);
            }

            $anexoa->fill([
                'empresa_id' => $empresa_id,
                'cpatient_id'=>$data['paciente']['id'],
                'paciente_id'=>null,
                'data_avaliacao' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->save();
        }

        $anexoa->opcoes()->delete();
        $anexoa->informacoes()->delete();



        foreach ($data['dados'] as $key => $g1) {
            if(isset($g1['descricao2'])){
                if($g1['descricao2']=='null' || $g1['descricao2']=='undefined'){
                    $g1['descricao2']=null;
                }
            }
            if(isset($g1['value_real'])){
                if($g1['value_real']=='null' || $g1['value_real']=='undefined'){
                    $g1['value_real']=null;
                }
            }
            if(isset($g1['value'])){
                if($g1['value']=='null' || $g1['value']=='undefined'){
                    $g1['value']=null;
                }
            }
            if(isset($g1['descricao'])){
                if($g1['descricao']=='null' || $g1['descricao']=='undefined'){
                    $g1['descricao']=null;
                }
            }
            $grupo1 = new OpcoesAnexoB();
            $grupo1->fill([
                'anexo_b_id'=>$anexoa->id,
                'categoria'=>$key,
                'descricao_value_2'=>(!isset($g1['descricao2']) || $g1['descricao2']=='')?null:$g1['descricao2'],
                'value_real'=>(!isset($g1['value_real']) || $g1['value_real']=='')?null:$g1['value_real'],

                'value'=>(!isset($g1['value']) || $g1['value']=='')?null:$g1['value'],

                'descricao_value'=>(!isset($g1['descricao']) || $g1['descricao']=='')?null:$g1['descricao'],
            ])->save();
        }

        // Log::info($data['informacoes_complementares']);


        // foreach ($data['informacoes_complementares'] as $key => $g1) {
        //     $grupo1 = new AnexoBInformacoes();
        //     $grupo1->fill([
        //         'anexo_b_id'=>$anexoa->id, 
        //         'categoria'=>$g1['label'],
        //         'value'=>$g1['value'],
        //         'telefone'=>$g1['telefone'],
        //         'cep'=>isset($g1['cep'])?$g1['cep']:null,
        //         'rua'=>isset($g1['rua'])?$g1['rua']:null,
        //         'numero'=>isset($g1['numero'])?$g1['numero']:null,
        //         'bairro'=>isset($g1['bairro'])?$g1['bairro']:null,
        //         'cidade'=>isset($g1['cidade'])?$g1['cidade']:null,
        //         'estado'=>isset($g1['estado'])?$g1['estado']:null,
        //         'complemento'=>isset($g1['complemento'])?$g1['complemento']:null,
        //     ])->save();
        // }
        if(isset($data['servicos_selecionados'])){
            $anexoa->servicos()->Sync($data['servicos_selecionados']);
        }
        else{
            $anexoa->servicos()->Sync([]);
        }

        return response()->json([
            'anexob' => $anexoa
        ]);
    }



    public function store_servico_anexo_b(AnexoBRequest $request){
        $data=$request->validated();
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
       
        $servico_anexo_b = new ServicoSocioAmbiental();

        $servico_anexo_b->fill([
            'empresa_id'=>$empresa_id
        ])->fill($data)->save();
        // $cuidados = Cuidado::where('ativo','=',1)->where('empresa_id','=',$empresa_id)->orderBy('descricao')->get();

        return response()->json([
            'servico'=>$servico_anexo_b
        ]);
    }
}
