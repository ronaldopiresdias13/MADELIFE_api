<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ordemservico extends Model
{
    protected $guarded = [];

    public function orcamento()
    {
        return $this->belongsTo('App\Orcamento');
    }
}
