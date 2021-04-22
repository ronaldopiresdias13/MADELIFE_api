<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ChamadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $mensagem=$this->mensagens()->orderBy('created_at','desc')->first();
        return [
            'id'=>$this->id,
            'prestador'=>$this->prestador()->first(),
            'mensagens'=>MensagemChamadoResource::collection($this->mensagens()->orderBy('created_at','asc')->get()),
            'nao_vistas'=>$this->mensagens()->where('atendente_id','<>',null)->where('visto','=',false)->count(),
            'assunto'=>$this->assunto,
            'tipo'=>$this->tipo,
            'protocolo'=>$this->protocolo,
            'finalizado'=>$this->finalizado,
            'empresa'=>$this->empresa()->first(),

            // 'updated_at'=>Carbon::parse($this->updated_at)->timestamp,
            'date'=>$mensagem!=null?Carbon::parse($mensagem->created_at)->format('Y-m-d H:i:s'):Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),

        ];
    }
}
