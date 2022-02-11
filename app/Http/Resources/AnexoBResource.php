<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnexoBResource extends JsonResource
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

        $data['opcoes']=$this->opcoes()->get();

        $data['informacoes']=$this->informacoes()->get();
        $data['servicos']=$this->servicos()->get();

        return $data;
    }
}
