<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $guarded = [];

    public function clientes()
    {
        return $this->hasMany('App\Cliente');
    }

    public function enderecos()
    {
        return $this->belongsToMany('App\Endereco', 'pessoa_endereco');
    }

    public function prestador()
    {
        return $this->hasOne('App\Prestador');
    }
}
