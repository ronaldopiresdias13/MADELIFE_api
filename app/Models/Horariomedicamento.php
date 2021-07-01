<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horariomedicamento extends Model
{
    protected $guarded = [];

    public function itensTranscricao()
    {
        return $this->belongsTo(TranscricaoProduto::class)->where('ativo', true);
    }
}
