<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $guarded = [];

    public function cliente()
    {
        return $this->hasOne('App\Cliente')->where('ativo', true);
    }

    public function enderecos()
    {
        return $this->belongsToMany('App\Endereco', 'pessoa_endereco')
            ->withPivot('id')
            ->wherePivot('ativo', true);
    }

    public function telefones()
    {
        return $this->belongsToMany('App\Telefone', 'pessoa_telefone')
            ->withPivot('id', 'tipo', 'descricao')
            ->wherePivot('ativo', true);
    }

    public function prestador()
    {
        return $this->hasOne('App\Prestador')->where('ativo', true);
    }

    public function profissional()
    {
        return $this->hasOne('App\Profissional')->where('ativo', true);
    }

    public function fornecedor()
    {
        return $this->hasOne('App\Fornecedor')->where('ativo', true);
    }

    public function emails()
    {
        return $this->belongsToMany('App\Email', 'pessoa_email')
            ->withPivot('id', 'tipo', 'descricao')
            ->wherePivot('ativo', true);
    }

    public function assinaturas()
    {
        return $this->belongsTo('App\Assinatura')
            ->where('ativo', true);
    }

    public function user()
    {
        return $this->hasOne('App\User')->where('ativo', true);
    }

    public function dadosbancario()
    {
        return $this->hasMany('App\Dadosbancario')->where('ativo', true);
    }

    public function conselhos()
    {
        return $this->hasMany('App\Conselho')->where('ativo', true);
    }

    public function responsavel()
    {
        return $this->hasOne('App\Responsavel');
    }

    public function tipopessoas()
    {
        return $this->hasMany('App\Tipopessoa')->where('ativo', true);
    }
    public function pagamentopessoas()
    {
        return $this->hasMany('App\Pagamentopessoa')->where('ativo', true);
    }
}
