<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $guarded = [];

    public function clientes()
    {
        return $this->hasMany(Cliente::class)->where('ativo', true);
    }

    public function acessos()
    {
        return $this->hasMany(Acesso::class)->where('ativo', true);
    }

    public function cnabs()
    {
        return $this->hasMany(RegistroCnab::class,'empresa_id','id');
    }

    public function chamados()
    {
        return $this->hasMany(Chamado::class,'empresa_id','id');
    }
}
