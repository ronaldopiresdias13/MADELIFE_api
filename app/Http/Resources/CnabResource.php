<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CnabResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'arquivo'=>$this->arquivo,
            'mes'=>$this->mes,
            'codigo_banco'=>$this->codigo_banco,
            'data'=>$this->data,
            'observacao'=>$this->observacao,
            'situacao'=>$this->situacao,
            'num_pagamentos'=>$this->pagamentos()->count(),
            'pagamentos'=>PagamentoResource::collection($this->pagamentos()->get())
        ];
    }
}
