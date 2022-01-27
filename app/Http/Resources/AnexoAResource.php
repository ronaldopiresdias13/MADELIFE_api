<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnexoAResource extends JsonResource
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
        $data['paciente']=$this->paciente()->with(['pessoa'])->first();
        $data['cpaciente']=$this->cpaciente()->first();

        $data['diagnosticos_principais']=$this->diagnosticos_principais()->get();
        $data['diagnosticos_secundarios']=$this->diagnosticos_secundarios()->get();

        $data['escalas_braden']=$this->escalas_braden()->get();

        $data['escalas_coma_glasgow']=$this->escalas_coma_glasgow()->get();

        $data['exames_fisicos']=$this->exames_fisicos()->get();


        return $data;
    }
}
