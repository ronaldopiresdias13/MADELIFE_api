<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnexoBRequest;
use App\Http\Resources\AnexoBEditResource;
use App\Http\Resources\AnexoBResource;
use App\Models\AnexoBInformacoes;
use App\Models\OpcoesAnexoB;
use App\Models\Paciente;
use App\Models\PlanilhaAnexoB;
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
            $anexoas = $anexoas->whereHas('paciente', function ($q) use ($request) {
                $q->whereHas('pessoa', function ($q2) use ($request) {
                    $q2->whereRaw('lower(nome) LIKE lower(?)', ['%' . $request->paciente . '%']);
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
        $pacientes = Paciente::selectRaw('
        pacientes.id as paciente_id, pacientes.pessoa_id as pessoa_paciente_id,p.nome as paciente_nome, 
        pacientes.sexo as paciente_sexo, r.id as responsavel_id, pr.nome as responsavel_nome, r.parentesco,
        r.pessoa_id as pessoa_responsavel_id
        ')->where('pacientes.empresa_id', '=', $empresa_id)
            ->join(DB::raw('pessoas as p'), 'p.id', '=', 'pacientes.pessoa_id')
            ->join(DB::raw('responsaveis as r'), 'r.id', '=', 'pacientes.responsavel_id')
            ->join(DB::raw('pessoas as pr'), 'r.pessoa_id', '=', 'pr.id')->get();

       
        return response()->json(['pacientes' => $pacientes]);
    }

    public function store_anexob(AnexoBRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $anexoa = new PlanilhaAnexoB();
        $anexoa->fill([
            'empresa_id' => $empresa_id,
            'paciente_id' => $data['paciente_id'],
            'data_avaliacao' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->save();

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

        foreach ($data['informacoes_complementares'] as $key => $g1) {
            $grupo1 = new AnexoBInformacoes();
            $grupo1->fill([
                'anexo_b_id'=>$anexoa->id, 
                'categoria'=>$g1['label'],
                'value'=>$g1['value'],
                'telefone'=>$g1['telefone'],
                'cep'=>isset($g1['cep'])?$g1['cep']:null,
                'rua'=>isset($g1['rua'])?$g1['rua']:null,
                'numero'=>isset($g1['numero'])?$g1['numero']:null,
                'bairro'=>isset($g1['bairro'])?$g1['bairro']:null,
                'cidade'=>isset($g1['cidade'])?$g1['cidade']:null,
                'estado'=>isset($g1['estado'])?$g1['estado']:null,
                'complemento'=>isset($g1['complemento'])?$g1['complemento']:null,
            ])->save();
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
        $pacientes = Paciente::selectRaw('
        pacientes.id as paciente_id, pacientes.pessoa_id as pessoa_paciente_id,p.nome as paciente_nome, 
        pacientes.sexo as paciente_sexo, r.id as responsavel_id, pr.nome as responsavel_nome, r.parentesco,
        r.pessoa_id as pessoa_responsavel_id
        ')->where('pacientes.empresa_id','=',$empresa_id)
        ->join(DB::raw('pessoas as p'),'p.id','=','pacientes.pessoa_id')
        ->join(DB::raw('responsaveis as r'),'r.id','=','pacientes.responsavel_id')
        ->join(DB::raw('pessoas as pr'),'r.pessoa_id','=','pr.id')->get();

        $anexoa = PlanilhaAnexoB::find($id);
        // $cuidados = Cuidado::where('ativo','=',1)->where('empresa_id','=',$empresa_id)->orderBy('descricao')->get();

        return response()->json([
            'anexob'=>AnexoBEditResource::make($anexoa),
           'pacientes'=>$pacientes]);
    }


    public function update_anexob(AnexoBRequest $request){
        $user = $request->user();
        $data = $request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $anexoa = PlanilhaAnexoB::find($data['anexo_b_id']);
        $anexoa->fill([
            'empresa_id' => $empresa_id,
            'paciente_id' => $data['paciente_id'],
            'data_avaliacao' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->save();

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

        Log::info($data['informacoes_complementares']);


        foreach ($data['informacoes_complementares'] as $key => $g1) {
            $grupo1 = new AnexoBInformacoes();
            $grupo1->fill([
                'anexo_b_id'=>$anexoa->id, 
                'categoria'=>$g1['label'],
                'value'=>$g1['value'],
                'telefone'=>$g1['telefone'],
                'cep'=>isset($g1['cep'])?$g1['cep']:null,
                'rua'=>isset($g1['rua'])?$g1['rua']:null,
                'numero'=>isset($g1['numero'])?$g1['numero']:null,
                'bairro'=>isset($g1['bairro'])?$g1['bairro']:null,
                'cidade'=>isset($g1['cidade'])?$g1['cidade']:null,
                'estado'=>isset($g1['estado'])?$g1['estado']:null,
                'complemento'=>isset($g1['complemento'])?$g1['complemento']:null,
            ])->save();
        }




        return response()->json([
            'anexob' => $anexoa
        ]);
    }
}
