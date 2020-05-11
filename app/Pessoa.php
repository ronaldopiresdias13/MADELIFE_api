<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $guarded = [];

    public function clientes()
    {
        return $this->hasOne('App\Cliente');
    }

    public function enderecos()
    {
        return $this->hasMany('App\Enderecos');
    }
}
