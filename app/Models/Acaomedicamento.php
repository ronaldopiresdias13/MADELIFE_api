<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acaomedicamento extends Model
{
    protected $guarded = [];

    public function transcricaoProduto()
    {
        return $this->belongsTo(TranscricaoProduto::class);
    }
}
