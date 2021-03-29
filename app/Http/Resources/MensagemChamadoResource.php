<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MensagemChamadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $prestador = $this->prestador()->first();
        
        $atendente = $this->atendente()->first();
       
        return [
            'id'=>$this->id,
            'atendente'=>$atendente,
            'prestador'=>$prestador,
            'message'=>$this->message,
            'uuid'=>$this->uuid,
            'visto'=>$this->visto,
            'type'=>$this->type,
            'arquivo'=>$this->arquivo,
            'chamado_id'=>$this->chamado_id,
            'created_at'=>Carbon::parse($this->created_at)->format('Y-m-d H:i'),
            // 'empresa'=>EmpresaChatResource::make($this->empresa()->first())
        ];
    }
}
