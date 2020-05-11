<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $guarded = [];

    public function clientes()
    {
        return $this->belongsTo('App\Cliente');
    }
}
