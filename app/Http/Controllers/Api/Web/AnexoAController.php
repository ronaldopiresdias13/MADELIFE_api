<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnexoARequest;
use App\Http\Resources\AnexoAEditResource;
use App\Http\Resources\AnexoAResource;
use App\Models\DiagnosticoPil;
use App\Models\EscalaBradenAnexoA;
use App\Models\EscalaComaGlasgowAnexoA;
use App\Models\ExameFisicoAnexoA;
use App\Models\Paciente;
use App\Models\PlanilhaAnexoA;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AnexoAController extends Controller
{
    public function get_anexosA(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $anexoas = PlanilhaAnexoA::where('empresa_id', '=', $empresa_id);
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
        if ($request->diagnostico != null && Str::length($request->diagnostico) > 0) {
            $anexoas = $anexoas->whereHas('diagnostico_principal', function ($q) use ($request) {
                $q->whereRaw('lower(nome) LIKE lower(?)', ['%' . $request->diagnostico . '%']);
            });
        }
        $anexoas = $anexoas->orderBy('created_at', 'desc')->paginate(15, ['*'], 'anexoas');
        $current_page_diagnostico = $anexoas->currentPage();
        $last_page_diagnostico = $anexoas->lastPage();
        $total_diagnostico = $anexoas->total();
        $per_page_diagnostico = $anexoas->perPage();
        return response()->json([
            'anexos' => AnexoAResource::collection($anexoas),
            'current_page_anexoA' => $current_page_diagnostico,
            'last_page_anexoA' => $last_page_diagnostico,
            'total_anexoA' => $total_diagnostico,
            'per_page_anexoA' => $per_page_diagnostico,
        ]);
    }

    public function getDadosAnexoA(Request $request)
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

        $diagnosticos_principais = DiagnosticoPil::where('flag', '=', 'Primário')->orderBy('nome', 'asc')->get();

        $diagnosticos_secundarios = DiagnosticoPil::where('flag', '=', 'Secundário')->orderBy('nome', 'asc')->get();

        // $cuidados = Cuidado::where('ativo','=',1)->where('empresa_id','=',$empresa_id)->orderBy('descricao')->get();

        return response()->json(['pacientes' => $pacientes, 'diagnosticos_principais' => $diagnosticos_principais, 'diagnosticos_secundarios' => $diagnosticos_secundarios]);
    }

    public function store_anexoa(AnexoARequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $anexoa = new PlanilhaAnexoA();
        $anexoa->fill([
            'diagnostico_principal_id' => $data['diagnostico_principal_id'],
            'empresa_id' => $empresa_id,
            'paciente_id' => $data['paciente_id'],
            'classificacao_escala_braden' => $data['classificacao_braden']['pontos'],
            'classificacao_coma_glasgow' => $data['classificacao_coma_glasbow']['pontos'],
            'intensidade_dor' => $data['intensidade_dor'],
            'diametros_pupilas' => $data['diametros_pupilas'],
            'data_avaliacao' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->save();

        $anexoa->diagnosticos_secundarios()->Sync($data['diagnostico_secundarios_id']);

        foreach ($data['dados_fisicos'] as $key => $g1) {
            $grupo1 = new ExameFisicoAnexoA();
            $grupo1->fill([
                'categoria' => $key,
                'anexo_a_id'=>$anexoa->id, 
                'descricao_value_2'=>(!isset($g1['descricao2']) || $g1['descricao2']=='')?null:$g1['descricao2'],
                'value'=>(!isset($g1['value_real']) || $g1['value_real']=='')?null:$g1['value_real'],
                'descricao_value'=>(!isset($g1['descricao']) || $g1['descricao']=='')?null:$g1['descricao']
            ])->save();
        }

        foreach ($data['escala_braden'] as $key => $g1) {
            $grupo1 = new EscalaBradenAnexoA();
            $grupo1->fill([
                'anexo_a_id'=>$anexoa->id, 
                'categoria'=>$g1['label'],
                'pontuacao'=>$g1['pontuacao_value'],
                'value'=>$g1['value']
            ])->save();
        }

        foreach ($data['escala_coma_glasgow'] as $key => $g1) {
            $grupo1 = new EscalaComaGlasgowAnexoA();
            $grupo1->fill([
                'anexo_a_id'=>$anexoa->id, 
                'categoria'=>$g1['label'],
                'pontuacao'=>$g1['pontuacao_value'],
                'value'=>$g1['value']
            ])->save();
        }




        return response()->json([
            'anexoa' => $anexoa
        ]);
    }

    public function delete_anexoa($id)
    {
        $user = request()->user();

        $empresa_id = $user->pessoa->profissional->empresa_id;
        $diag = PlanilhaAnexoA::where('id','=',$id)->where('empresa_id','=',$empresa_id)->delete();
        return response()->json([
            'status' => true,
        ]);
    }

    public function getAnexoAEdit(Request $request,$id){
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

        $anexoa = PlanilhaAnexoA::find($id);
        // $cuidados = Cuidado::where('ativo','=',1)->where('empresa_id','=',$empresa_id)->orderBy('descricao')->get();

        return response()->json([
            'anexoa'=>AnexoAEditResource::make($anexoa),
           'pacientes'=>$pacientes,'diagnosticos_principais'=>$diagnosticos_principais,'diagnosticos_secundarios'=>$diagnosticos_secundarios]);
    }


    public function update_anexoa(AnexoARequest $request){
        $user = $request->user();
        $data = $request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $anexoa = PlanilhaAnexoA::find($data['anexo_a_id']);
        $anexoa->fill([
            'diagnostico_principal_id' => $data['diagnostico_principal_id'],
            'empresa_id' => $empresa_id,
            'paciente_id' => $data['paciente_id'],
            'classificacao_escala_braden' => $data['classificacao_braden']['pontos'],
            'classificacao_coma_glasgow' => $data['classificacao_coma_glasbow']['pontos'],
            'intensidade_dor' => $data['intensidade_dor'],
            'diametros_pupilas' => $data['diametros_pupilas'],
            'data_avaliacao' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->save();

        $anexoa->diagnosticos_secundarios()->Sync($data['diagnostico_secundarios_id']);

        $anexoa->exames_fisicos()->delete();
        $anexoa->escalas_braden()->delete();
        $anexoa->escalas_coma_glasgow()->delete();

        foreach ($data['dados_fisicos'] as $key => $g1) {
            $grupo1 = new ExameFisicoAnexoA();
            $grupo1->fill([
                'categoria' => $key,
                'anexo_a_id'=>$anexoa->id, 
                'descricao_value_2'=>(!isset($g1['descricao2']) || $g1['descricao2']=='')?null:$g1['descricao2'],
                'value'=>(!isset($g1['value_real']) || $g1['value_real']=='')?null:$g1['value_real'],
                'descricao_value'=>(!isset($g1['descricao']) || $g1['descricao']=='')?null:$g1['descricao']
            ])->save();
        }


        foreach ($data['escala_braden'] as $key => $g1) {
            $grupo1 = new EscalaBradenAnexoA();
            $grupo1->fill([
                'anexo_a_id'=>$anexoa->id, 
                'categoria'=>$g1['label'],
                'pontuacao'=>$g1['pontuacao_value'],
                'value'=>$g1['value']
            ])->save();
        }

        foreach ($data['escala_coma_glasgow'] as $key => $g1) {
            $grupo1 = new EscalaComaGlasgowAnexoA();
            $grupo1->fill([
                'anexo_a_id'=>$anexoa->id, 
                'categoria'=>$g1['label'],
                'pontuacao'=>$g1['pontuacao_value'],
                'value'=>$g1['value']
            ])->save();
        }




        return response()->json([
            'anexoa' => $anexoa
        ]);
    }
}
