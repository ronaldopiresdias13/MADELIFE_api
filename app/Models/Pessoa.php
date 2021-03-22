<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    // use FiltroEmpresaProfissional;

    protected $guarded = [];

    public function cliente()
    {
        return $this->hasOne(Cliente::class)->where('ativo', true);
    }

    public function enderecos()
    {
        return $this->belongsToMany(Endereco::class, 'pessoa_endereco')
            ->withPivot('id')
            ->wherePivot('ativo', true);
    }

    public function telefones()
    {
        return $this->belongsToMany(Telefone::class, 'pessoa_telefone')
            ->withPivot('id', 'tipo', 'descricao')
            ->wherePivot('ativo', true);
    }

    public function prestador()
    {
        return $this->hasOne(Prestador::class)->where('ativo', true);
    }

    public function profissional()
    {
        return $this->hasOne(Profissional::class)->where('ativo', true);
    }

    public function fornecedor()
    {
        return $this->hasOne(Fornecedor::class)->where('ativo', true);
    }

    public function emails()
    {
        return $this->belongsToMany(Email::class, 'pessoa_email')
            ->withPivot('id', 'tipo', 'descricao')
            ->wherePivot('ativo', true);
    }

    public function assinaturas()
    {
        return $this->belongsTo(Assinatura::class)
            ->where('ativo', true);
    }

    public function user()
    {
        return $this->hasOne(User::class)->where('ativo', true);
    }

    public function dadosbancario()
    {
        return $this->hasMany(Dadosbancario::class)->where('ativo', true);
    }

    public function conselhos()
    {
        return $this->hasMany(Conselho::class)->where('ativo', true);
    }

    public function responsavel()
    {
        return $this->hasOne(Responsavel::class);
    }

    public function tipopessoas()
    {
        return $this->hasMany(Tipopessoa::class)->where('ativo', true);
    }
    public function pagamentopessoas()
    {
        return $this->hasMany(Pagamentopessoa::class)->where('ativo', true);
    }

    public function tipos()
    {
        return $this->hasMany(Tipopessoa::class);
    }

    public function cnabpessoas()
    {
        return $this->hasMany(CnabPessoa::class,'pessoa_id','id');
    }



    public function mensagens()
    {
        return $this->hasMany(ConversaMensagem::class,'sender_id','id');
    }

    public function conversas_sender()
    {
        return $this->hasMany(Conversa::class,'sender_id','id');
    }

    public function conversas_receive()
    {
        return $this->hasMany(Conversa::class,'receive_id','id');
    }
}
