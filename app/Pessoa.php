<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $guarded = [];

    public function cliente()
    {
        return $this->hasOne('App\Cliente');
    }

    public function enderecos()
    {
        return $this->belongsToMany('App\Endereco', 'pessoa_endereco');
    }

    public function telefones()
    {
        return $this->belongsToMany('App\Telefone', 'pessoa_telefone')
        ->withPivot('id', 'tipo', 'descricao')
        ->wherePivot('ativo', true);
    }

    public function prestador()
    {
        return $this->hasOne('App\Prestador');
    }

    public function profissional()
    {
        return $this->hasOne('App\Profissional');
    }

    public function fornecedor()
    {
        return $this->hasOne('App\Fornecedor');
    }

    public function emails()
    {
        return $this->belongsToMany('App\Email', 'pessoa_email')
        ->withPivot('id', 'tipo', 'descricao')
        ->wherePivot('ativo', true);
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function dadosbancario()
    {
        return $this->hasMany('App\Dadosbancario');
    }

    public function conselhos()
    {
        return $this->hasMany('App\Conselho');
    }
}
