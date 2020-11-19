<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acaomedicamento extends Model
{
    protected $guarded = [];

    public function transcricaoProduto()
    {
        return $this->belongsTo(TranscricaoProduto::class);
    }
}
