<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanilhaAbmidResource extends JsonResource
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
        $data['diagnosticos_principais']=$this->diagnosticos_principais()->get();
        $data['diagnosticos_secundarios']=$this->diagnosticos_secundarios()->get();


        return $data;
    }
}
