<?php

namespace App\Http\Resources;

use App\Models\Paciente;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PlanilhaAbmidEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data= parent::toArray($request);

        $paciente = Paciente::selectRaw('
        pacientes.id as paciente_id, pacientes.pessoa_id as pessoa_paciente_id,p.nome as paciente_nome, 
        pacientes.sexo as paciente_sexo, r.id as responsavel_id, pr.nome as responsavel_nome, r.parentesco,
        r.pessoa_id as pessoa_responsavel_id
        ')->where('pacientes.empresa_id','=',$this->empresa_id)
        ->join(DB::raw('pessoas as p'),'p.id','=','pacientes.pessoa_id')
        ->join(DB::raw('responsaveis as r'),'r.id','=','pacientes.responsavel_id')
        ->join(DB::raw('pessoas as pr'),'r.pessoa_id','=','pr.id')->where('pacientes.id','=',$this->paciente_id)->first();
        
        $data['paciente']=$paciente;

        $data['diagnosticos_secundarios'] = $this->diagnosticos_secundarios()->get();
        $data['diagnostico_principal'] = $this->diagnostico_principal()->first();

        $data['cuidados'] = $this->itens()->orderBy('descricao','asc')->get();
       


        return $data;
    }
}
