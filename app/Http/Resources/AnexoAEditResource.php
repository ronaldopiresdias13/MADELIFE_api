<?php

namespace App\Http\Resources;

use App\Models\Paciente;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AnexoAEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data =  parent::toArray($request);
        $paciente = Paciente::selectRaw('pacientes.id as id,pacientes.pessoa_id as pessoa_id,
        pacientes.id as paciente_id, pacientes.pessoa_id as pessoa_paciente_id,p.nome as paciente_nome, 
        pacientes.sexo as paciente_sexo, r.id as responsavel_id, pr.nome as responsavel_nome, r.parentesco,
        r.pessoa_id as pessoa_responsavel_id
        ')->where('pacientes.empresa_id','=',$this->empresa_id)
        ->join(DB::raw('pessoas as p'),'p.id','=','pacientes.pessoa_id')
        ->join(DB::raw('responsaveis as r'),'r.id','=','pacientes.responsavel_id')
        ->join(DB::raw('pessoas as pr'),'r.pessoa_id','=','pr.id')->where('pacientes.id','=',$this->paciente_id)->with(['pessoa.enderecos.cidade','responsavel.pessoa.telefones'])->first();
        
        $data['paciente']=$paciente;
        $data['diagnosticos_principais']=$this->diagnosticos_principais()->get();
        $data['diagnosticos_secundarios']=$this->diagnosticos_secundarios()->get();

        $data['escalas_braden']=$this->escalas_braden()->get();

        $data['escalas_coma_glasgow']=$this->escalas_coma_glasgow()->get();

        $data['exames_fisicos']=$this->exames_fisicos()->get();


        return $data;
    }
}
