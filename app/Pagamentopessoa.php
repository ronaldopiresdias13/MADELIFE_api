<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pagamentopessoa extends Model
{
    protected $guarded = [];

    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa');
    }
    public function ordemservico()
    {
        return $this->belongsTo('App\Ordemservico');
    }
}
