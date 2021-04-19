<?php

namespace App\Http\Controllers\Api\Web\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChamadoAtendenteResource;
use App\Models\Chamado;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function get_dados()
    {
        $user = request()->user();
        $pessoa = $user->pessoa;
        $profissinal = $pessoa->profissional()->first();
        if ($profissinal == null) {

            return response()->json(['chamados_enfermagem' => [], 'chamados_ti' => []]);
        }
        if ($user->acessos()->where('acessos.nome', '=', 'Área Clínica')->first() != null) {
            $chamados_enfermagem_final = [];
            $chamados_enfermagem = Chamado::where('empresa_id', '=', $profissinal->empresa_id)->has('mensagens')->where('finalizado', '=', false)->where('tipo', '=', 'Enfermagem')->with(['mensagens' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }])->orderBy('created_at','desc')->get();

            foreach ($chamados_enfermagem as $ti) {
                if ($ti->mensagens[0]->atendente_id == null) {
                    array_push($chamados_enfermagem_final, $ti);
                }
            }
            $chamados_enfermagem = ChamadoAtendenteResource::collection($chamados_enfermagem_final);
        } else {
            $chamados_enfermagem = [];
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

        return response()->json(['chamados_enfermagem' => $chamados_enfermagem, 'chamados_ti' => $chamados_ti]);
    }
}
