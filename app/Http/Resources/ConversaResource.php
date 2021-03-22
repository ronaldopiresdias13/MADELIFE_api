<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sender=$this->sender()->first();
        $receive=$this->receive()->first();
        return [
            'id'=>$this->id,
            'sender'=>$sender,
            'receive'=>$receive,
            'date'=>Carbon::parse($this->mensagens()->orderBy('created_at','desc')->first()->created_at)->format('Y-m-d H:i:s'),
            'nao_vistas_sender'=>$this->mensagens()->where('visto','=',false)->where('conversas_mensagens.sender_id',$sender->id)->count(),
            'nao_vistas_receive'=>$this->mensagens()->where('visto','=',false)->where('conversas_mensagens.sender_id',$receive->id)->count(),
            'mensagens'=>ConversaMensagemResource::collection($this->mensagens()->orderBy('created_at','asc')->get()),
            'created_at'=>Carbon::parse($this->created_at)->format('Y-m-d H:i:s')

        ];
    }
}
