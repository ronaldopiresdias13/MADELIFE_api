<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiagnosticoRequest;
use App\Http\Requests\PilRequest;
use App\Http\Resources\AnexoAResource;
use App\Http\Resources\DiagnosticoResource;
use App\Http\Resources\NeadResource;
use App\Http\Resources\PlanilhaAbmidResource;
use App\Http\Resources\PlanilhaPilResource;
use App\Models\Cuidado;
use App\Models\DiagnosticoPil;
use App\Models\HorarioMedicamentoPil;
use App\Models\MedicamentoPil;
use App\Models\Nead;
use App\Models\Paciente;
use App\Models\PlanilhaAbmid;
use App\Models\PlanilhaAnexoA;
use App\Models\PlanilhaAnexoB;
use App\Models\PlanilhaPil;
use App\Models\PrescricaoBPil;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PilController extends Controller
{
    public function get_pils(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $pils = PlanilhaPil::where('empresa_id', '=', $empresa_id);
        if ($request->inicio != null && $request->inicio != '') {
            $pils = $pils->where('created_at', '>=', $request->inicio . ' 00:00:00');
        }
        if ($request->fim != null && $request->fim != '') {
            $pils = $pils->where('created_at', '<=', $request->fim . ' 23:59:00');
        }
        if ($request->paciente != null && Str::length($request->paciente) > 0) {
            $pils = $pils->whereHas('paciente', function ($q) use ($request) {
                $q->whereHas('pessoa', function ($q2) use ($request) {
                    $q2->whereRaw('lower(nome) LIKE lower(?)', ['%' . $request->paciente . '%']);
                });
            });
        }
        if ($request->diagnostico != null && Str::length($request->diagnostico) > 0) {
            $pils = $pils->whereHas('diagnostico_primario', function ($q) use ($request) {
                $q->whereRaw('lower(nome) LIKE lower(?)', ['%' . $request->diagnostico . '%']);
            });
        }
        $pils = $pils->orderBy('created_at', 'desc')->paginate(15, ['*'], 'pils');
        $current_page_diagnostico = $pils->currentPage();
        $last_page_diagnostico = $pils->lastPage();
        $total_diagnostico = $pils->total();
        $per_page_diagnostico = $pils->perPage();
        return response()->json([
            'pils' => PlanilhaPilResource::collection($pils),
            'current_page_pil' => $current_page_diagnostico,
            'last_page_pil' => $last_page_diagnostico,
            'total_pil' => $total_diagnostico,
            'per_page_pil' => $per_page_diagnostico,
        ]);
    }

    public function getDadosPil(Request $request)
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
            ->join(DB::raw('pessoas as pr'), 'r.pessoa_id', '=', 'pr.id')->with(['pessoa.enderecos.cidade','responsavel.pessoa.telefones'])->get();

        $diagnosticos_principais = DiagnosticoPil::where('flag', '=', 'Primário')->orderBy('nome', 'asc')->get();

        $diagnosticos_secundarios = DiagnosticoPil::where('flag', '=', 'Secundário')->orderBy('nome', 'asc')->get();

        // $cuidados = Cuidado::where('ativo','=',1)->where('empresa_id','=',$empresa_id)->orderBy('descricao')->get();

        return response()->json(['medicamentos' => [], 'cuidados' => [], 'pacientes' => $pacientes, 'diagnosticos_principais' => $diagnosticos_principais, 'diagnosticos_secundarios' => $diagnosticos_secundarios]);
    }

    public function getPil(Request $request, $id)
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
            ->join(DB::raw('pessoas as pr'), 'r.pessoa_id', '=', 'pr.id')->with(['pessoa.enderecos.cidade','responsavel.pessoa.telefones'])->get();

        $diagnosticos_principais = DiagnosticoPil::where('flag', '=', 'Primário')->orderBy('nome', 'asc')->get();

        $diagnosticos_secundarios = DiagnosticoPil::where('flag', '=', 'Secundário')->orderBy('nome', 'asc')->get();

        $pil = PlanilhaPil::where('id', '=', $id)->where('empresa_id', '=', $empresa_id)->first();
        // $cuidados = Cuidado::where('ativo','=',1)->where('empresa_id','=',$empresa_id)->orderBy('descricao')->get();
        $paciente_selecionado = Paciente::selectRaw('pacientes.id as id,pacientes.pessoa_id as pessoa_id,
        pacientes.id as paciente_id, pacientes.pessoa_id as pessoa_paciente_id,p.nome as paciente_nome, 
        pacientes.sexo as paciente_sexo, r.id as responsavel_id, pr.nome as responsavel_nome, r.parentesco,
        r.pessoa_id as pessoa_responsavel_id
        ')->where('pacientes.empresa_id', '=', $empresa_id)
            ->join(DB::raw('pessoas as p'), 'p.id', '=', 'pacientes.pessoa_id')
            ->join(DB::raw('responsaveis as r'), 'r.id', '=', 'pacientes.responsavel_id')
            ->join(DB::raw('pessoas as pr'), 'r.pessoa_id', '=', 'pr.id')->where('pacientes.id', '=', $pil->paciente_id)->with(['pessoa.enderecos.cidade','responsavel.pessoa.telefones'])->first();

        $prescricoes_a = $pil->prescricoes_a()->get();

        $prescricoes_b = [];
        $presB = PrescricaoBPil::where('pil_id', '=', $pil->id)->orderBy('created_at', 'asc')->get();
        foreach ($presB as $pr) {
            $cuidado = $pr->cuidado()->first();
            $cuidado['diagnosticos_selecionados'] = $pr->diagnosticos_secundarios()->get();
            array_push($prescricoes_b, $cuidado);
        }

        $medicamentos_ = [];

        $medics = MedicamentoPil::where('pil_id', '=', $pil->id)->orderBy('created_at', 'asc')->get();

        foreach ($medics as $md) {
            $medicamento = $md->medicamento()->first()->jsonserialize();
            $medicamento['dados'] = [];
            $medicamento['dados']['frequencia'] = $md->frequencia;
            $medicamento['dados']['via'] = $md->via;
            $medicamento['dados']['horarios'] = HorarioMedicamentoPil::where('medicamento_pil_id', '=', $md->id)
                ->where('pil_id', '=', $pil->id)->orderBy('created_at', 'asc')->get()->pluck('horario');

            array_push($medicamentos_, $medicamento);
        }

        $data_nead = Nead::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $pil->paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
        $data_abmid = PlanilhaAbmid::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $pil->paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
        $data_anexoa = PlanilhaAnexoA::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $pil->paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();



        return response()->json([
            'paciente_selecionado' => $paciente_selecionado,
            'pil' => PlanilhaPilResource::make($pil),
            'prescricoes_a' => $prescricoes_a,
            'prescricoes_b' => $prescricoes_b,
            'medicamentos_' => $medicamentos_,
            'data_nead' => $data_nead == null ? null : NeadResource::make($data_nead), 'data_abmid' => $data_abmid == null ? null : PlanilhaAbmidResource::make($data_abmid), 'data_anexoa' => $data_anexoa == null ? null : AnexoAResource::make($data_anexoa),

            'medicamentos' => [], 'cuidados' => [], 'pacientes' => $pacientes, 'diagnosticos_principais' => $diagnosticos_principais, 'diagnosticos_secundarios' => $diagnosticos_secundarios
        ]);
    }


    public function getCuidados(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        Log::info($request->search);
        $cuidados = Cuidado::whereRaw('lower(descricao) LIKE lower(?)', ['%' . $request->search . '%'])->where('ativo', '=', 1)->where('empresa_id', '=', $empresa_id)->orderBy('descricao')->get()->take(30);
        return response()->json(['cuidados' => $cuidados]);
    }


    public function getMedicamentos(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        Log::info($request->search);
        $medicamentos = Produto::whereRaw('lower(descricao) LIKE lower(?)', ['%' . $request->search . '%'])->where('ativo', '=', 1)->where('empresa_id', '=', $empresa_id)->where('categoria', '=', 'Medicamento')->orderBy('descricao', 'asc')->get()->take(30);

        return response()->json(['medicamentos' => $medicamentos]);
    }


    public function store_pil(PilRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $nead_check = PlanilhaPil::where('empresa_id','=',$empresa_id)->where('paciente_id','=',$data['paciente']['paciente_id'])->first();
        if($nead_check!=null ){
            return response()->json(['status'=>false, 'message'=>'Esse paciente já possui uma PIL cadastrada']);
        }

        $pil = new PlanilhaPil();
        $pil->fill([
            'diagnostico_primario_id' => $data['diagnosticos_principais'][0]['id'],
            'empresa_id' => $empresa_id,
            'paciente_id' => $data['paciente']['paciente_id'],
            'revisao' => $data['revisao'],

            'prognostico' => $data['prognostico'],
            'avaliacao_prescricoes' => $data['avaliacao_prescricoes'],
            'justificativa_revisao' => $data['justificativa_revisao'],
            'evolucao_base' => $data['evolucao_base'],
        ])->save();

        $dias_secundarios = [];
        foreach ($data['diagnosticos_secundarios'] as $diag_secundario) {
            array_push($dias_secundarios, $diag_secundario['id']);
        }
        $pil->diagnosticos_secundarios()->Sync($dias_secundarios);

        $diagnosticos_principais = [];
        foreach ($data['diagnosticos_principais'] as $diag_principal) {
            array_push($diagnosticos_principais, $diag_principal['id']);
        }
        $pil->diagnosticos_principais()->Sync($diagnosticos_principais);

        $prescAs = [];
        foreach ($data['prescricoes_a'] as $pres_a) {
            array_push($prescAs, $pres_a['id']);
        }

        $pil->prescricoes_a()->Sync($prescAs);


        foreach ($data['prescricoes_b'] as $pres_b) {
            $pres_b_pil = new PrescricaoBPil();
            $pres_b_pil->fill([
                'pil_id' => $pil->id,
                'cuidado_id' => $pres_b['id']
            ])->save();

            $dias_secundarios = [];
            foreach ($pres_b['diagnosticos_selecionados'] as $diag_s) {
                array_push($dias_secundarios, $diag_s['id']);
            }
            $pres_b_pil->diagnosticos_secundarios()->Sync($dias_secundarios);
        }


        foreach ($data['medicamentos'] as $medicamento) {
            $medic = new MedicamentoPil();
            $medic->fill([
                'medicamento_id' => $medicamento['id'],
                'frequencia' => $medicamento['dados']['frequencia'],
                'via' => $medicamento['dados']['via'],
                'pil_id' => $pil->id,
            ])->save();

            foreach ($medicamento['dados']['horarios'] as $hor) {
                $horario = new HorarioMedicamentoPil();
                $horario->fill([
                    'pil_id' => $pil->id,
                    'medicamento_pil_id' => $medic->id,
                    'horario' => $hor
                ])->save();
            }
        }


        return response()->json(['status' => true]);
    }



    public function update_pil(PilRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $pil = PlanilhaPil::where('id', '=', $data['pil_id'])->where('empresa_id', '=', $empresa_id)->first();

        $nead_check = PlanilhaPil::where('empresa_id','=',$empresa_id)->where('paciente_id','=',$data['paciente']['paciente_id'])->first();
        if($nead_check!=null && $nead_check->id!=$pil->id){
            return response()->json(['status'=>false, 'message'=>'Esse paciente já possui uma PIL cadastrada']);
        }
        
        $pil->fill([
            'diagnostico_primario_id' => $data['diagnosticos_principais'][0]['id'],
            'empresa_id' => $empresa_id,
            'paciente_id' => $data['paciente']['paciente_id'],
            'revisao' => $data['revisao'],

            'prognostico' => $data['prognostico'],
            'avaliacao_prescricoes' => $data['avaliacao_prescricoes'],
            'justificativa_revisao' => $data['justificativa_revisao'],
            'evolucao_base' => $data['evolucao_base'],
        ])->save();

        $dias_secundarios = [];
        foreach ($data['diagnosticos_secundarios'] as $diag_secundario) {
            array_push($dias_secundarios, $diag_secundario['id']);
        }
        $pil->diagnosticos_secundarios()->Sync($dias_secundarios);

        $diagnosticos_principais = [];
        foreach ($data['diagnosticos_principais'] as $diag_principal) {
            array_push($diagnosticos_principais, $diag_principal['id']);
        }
        $pil->diagnosticos_principais()->Sync($diagnosticos_principais);


        $prescAs = [];
        foreach ($data['prescricoes_a'] as $pres_a) {
            array_push($prescAs, $pres_a['id']);
        }

        $pil->prescricoes_a()->Sync($prescAs);

        $pil->prescricoes_b()->delete();
        foreach ($data['prescricoes_b'] as $pres_b) {
            $pres_b_pil = new PrescricaoBPil();
            $pres_b_pil->fill([
                'pil_id' => $pil->id,
                'cuidado_id' => $pres_b['id']
            ])->save();

            $dias_secundarios = [];
            foreach ($pres_b['diagnosticos_selecionados'] as $diag_s) {
                array_push($dias_secundarios, $diag_s['id']);
            }
            $pres_b_pil->diagnosticos_secundarios()->Sync($dias_secundarios);
        }


        $pil->medicamentos()->delete();

        foreach ($data['medicamentos'] as $medicamento) {
            $medic = new MedicamentoPil();
            $medic->fill([
                'medicamento_id' => $medicamento['id'],
                'frequencia' => $medicamento['dados']['frequencia'],
                'via' => $medicamento['dados']['via'],
                'pil_id' => $pil->id,
            ])->save();

            foreach ($medicamento['dados']['horarios'] as $hor) {
                $horario = new HorarioMedicamentoPil();
                $horario->fill([
                    'pil_id' => $pil->id,
                    'medicamento_pil_id' => $medic->id,
                    'horario' => $hor
                ])->save();
            }
        }


        return response()->json(['status' => true]);
    }


    public function delete_pil($id)
    {
        $user = request()->user();

        $empresa_id = $user->pessoa->profissional->empresa_id;
        $diag = PlanilhaPil::where('id', '=', $id)->where('empresa_id', '=', $empresa_id)->delete();
        return response()->json([
            'status' => true,
        ]);
    }


    //Diagnósticos secundários


    public function get_related_paciente($type, $paciente_id)
    {
        $user = request()->user();

        $empresa_id = $user->pessoa->profissional->empresa_id;
        if ($type == 'pil') {
            $data = Nead::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            if ($data == null) {
                $data = PlanilhaAbmid::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            }
            if ($data == null) {
                $data = PlanilhaAnexoA::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            }

            $data_nead = Nead::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            $data_abmid = PlanilhaAbmid::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            $data_anexoa = PlanilhaAnexoA::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();


            if ($data == null) {
                return response()->json(['data' => null, 
                'data_nead' => $data_nead == null ? null : NeadResource::make($data_nead), 'data_abmid' => $data_abmid == null ? null : PlanilhaAbmidResource::make($data_abmid), 'data_anexoa' => $data_anexoa == null ? null : AnexoAResource::make($data_anexoa)
            ]);
            } else {
                return response()->json(['data' => $data, 'data_nead' => $data_nead == null ? null : NeadResource::make($data_nead), 'data_abmid' => $data_abmid == null ? null : PlanilhaAbmidResource::make($data_abmid), 'data_anexoa' => $data_anexoa == null ? null : AnexoAResource::make($data_anexoa)]);
            }
        } else if ($type == 'nead') {
            $data = PlanilhaPil::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            if ($data == null) {
                $data = PlanilhaAbmid::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            }
            if ($data == null) {
                $data = PlanilhaAnexoA::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            }

            if ($data == null) {
                return response()->json(['data' => null]);
            } else {
                return response()->json(['data' => $data]);
            }
        } else if ($type == 'abmid') {
            $data = PlanilhaPil::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            if ($data == null) {
                $data = Nead::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            }
            if ($data == null) {
                $data = PlanilhaAnexoA::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            }

            if ($data == null) {
                return response()->json(['data' => null]);
            } else {
                return response()->json(['data' => $data]);
            }
        } else if ($type == 'anexoa') {
            $data = PlanilhaPil::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            if ($data == null) {
                $data = Nead::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            }
            if ($data == null) {
                $data = PlanilhaAbmid::where('empresa_id', '=', $empresa_id)->where('paciente_id', '=', $paciente_id)->with(['diagnosticos_principais', 'diagnosticos_secundarios'])->first();
            }

            if ($data == null) {
                return response()->json(['data' => null]);
            } else {
                return response()->json(['data' => $data]);
            }
        } else {
            return response()->json(['data' => null]);
        }
    }
}
