<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestador extends Model
{
    protected $table = 'prestadores';
    protected $guarded = [];

    public function formacoes()
    {
        return $this->belongsToMany(Formacao::class, 'prestador_formacao')->withPivot('id')->wherePivot('ativo', true);
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function ordemservicos()
    {
        return $this->hasMany(OrdemservicoPrestador::class)->where('ativo', true);
    }

    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'prestador_servico')->withPivot('id')->wherePivot('ativo', true);
    }

    // public function empresas()
    // {
    //     return $this->belongsToMany(Empresa::class, 'empresa_prestador')->withPivot('id')->wherePivot('ativo', true);
    // }

    public function empresas()
    {
        return $this->hasMany(EmpresaPrestador::class)->where('ativo', true);
    }
}
