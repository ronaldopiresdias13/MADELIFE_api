<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordemservico extends Model
{
    protected $guarded = [];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class);
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }

    public function transcricoes()
    {
        return $this->hasMany(Transcricao::class)->where('ativo', true);
    }

    public function escalas()
    {
        return $this->hasMany(Escala::class)->where('ativo', true);
    }

    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'ordemservico_servico')
            ->withPivot(
                'id',
                'descricao',
                'valordiurno',
                'valornoturno'
            )->wherePivot('ativo', true);
    }

    public function prestadores()
    {
        return $this->belongsToMany(Prestador::class, 'ordemservico_prestador')
            ->wherePivot('ativo', true);
    }

    public function ordemservicoPrestador()
    {
        return $this->hasMany(OrdemservicoPrestador::class)->where('ativo', true);
    }
    public function acessos()
    {
        return $this->belongsToMany(Acesso::class, 'ordemservico_acessos')->withPivot('id', 'check');
    }
}
