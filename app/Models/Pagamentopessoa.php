<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pagamentopessoa extends Model
{
    use SoftDeletes;

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
