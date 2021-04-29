<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OcorrenciaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $chamados=ChamadoAtendenteResource::collection($this->chamados()->get());
        return [
            'id'=>$this->id,
            'chamados'=>$chamados,
            'tipo'=>$this->tipo, 
            'situacao'=>$this->situacao,
            'transcricao_produto_id'=>$this->transcricao_produto_id,
            'escala_id'=>$this->escala_id,
            'horario'=>$this->horario,
            'empresa_id'=>$this->empresa_id,
            'pessoas'=>$this->pessoas()->get(),
            'escala'=>$this->escala()->first(),
            'paciente'=>$this->paciente()->first(),
            'responsavel'=>$this->responsavel()->first(),

            'transcricao_produto'=>$this->transcricao_produto()->with('produto','horariomedicamentos')->first(),
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }
}
