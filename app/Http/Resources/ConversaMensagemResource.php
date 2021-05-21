<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversaMensagemResource extends JsonResource
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
            'sender'=>$this->sender()->first(),
            'type'=>$this->type,
            'message'=>$this->message,
            'arquivo'=>$this->arquivo,
            'uuid'=>$this->uuid,
            'visto'=>$this->visto,
            'created_at'=>Carbon::parse($this->created_at)->format('Y-m-d H:i:s')
        ];
    }
}
