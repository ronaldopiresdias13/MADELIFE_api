<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamentopessoa extends Model
{
    protected $guarded = [];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
    public function ordemservico()
    {
        return $this->belongsTo(Ordemservico::class);
    }
}
