<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanilhaPilResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data =  parent::toArray($request);
        $data['paciente']=$this->paciente()->with(['pessoa'])->first();
        $data['diagnostico_primario']=$this->diagnostico_primario()->first();
        $data['diagnosticos_secundarios']=$this->diagnosticos_secundarios()->get();
        $data['prescricoes_a']=$this->prescricoes_a()->get();
        $data['prescricoes_b']=$this->prescricoes_b()->with(['cuidado','diagnosticos_secundarios'])->get();

        $data['medicamentos']=$this->medicamentos()->with(['medicamento','horarios'])->get();


        return $data;
    }
}
