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

    public function telefones()
    {
        return $this->belongsToMany('App\Telefone', 'pessoa_telefone')->withPivot('tipo', 'descricao');
    }

    public function prestador()
    {
        return $this->hasOne('App\Prestador');
    }

    public function fornecedor()
    {
        return $this->hasOne('App\Fornecedor');
    }

    public function emails()
    {
        return $this->belongsToMany('App\Email', 'pessoa_email')->withPivot('tipo', 'descricao');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
