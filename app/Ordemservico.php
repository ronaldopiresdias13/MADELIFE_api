<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ordemservico extends Model
{
    protected $guarded = [];

    public function orcamentos()
    {
        return $this->belongsTo('App\Orcamento', 'orcamento_id');
    }
}
