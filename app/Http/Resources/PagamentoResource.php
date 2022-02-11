<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PagamentoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $pessoa=$this->pessoa()->first();
        return [
            'id'=>$this->id,
            'nome'=>$pessoa->nome,
            'tipos'=>$pessoa->tipos()->get()->pluck('tipo'),
            'pessoa_id'=>$pessoa->id, 
            'valor'=>$this->valor,
            'status'=>$this->status,
            'agencia'=>$this->agencia,
            'conta'=>$this->conta,
            'digito'=>$this->digito,
            'banco'=>$this->banco,
            'cpfcnpj'=>$this->pessoa->cpfcnpj
        ];
    }
}
