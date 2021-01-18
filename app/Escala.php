<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escala extends Model
{
    protected $guarded = [];

    public function cuidados()
    {
        return $this->belongsToMany(Cuidado::class, 'cuidado_escalas')
            ->withPivot(
                'id',
                'data',
                'hora',
                'status'
            )->wherePivot('ativo', true);
    }

    public function pontos()
    {
        return $this->hasMany(Ponto::class)->where('ativo', true);
    }

    public function relatorios()
    {
        return $this->hasMany(Relatorio::class)->where('ativo', true);
    }

    public function monitoramentos()
    {
        return $this->hasMany(Monitoramentoescala::class); //->where('ativo', true);
    }

    public function ordemservico()
    {
        return $this->belongsTo(Ordemservico::class);
    }

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }

    public function acaoMedicamentos()
    {
        return $this->hasMany(Acaomedicamento::class)->where('ativo', true);
    }

    public function formacao()
    {
        return $this->belongsTo(Formacao::class);
    }

    public function relatorioescalas()
    {
        return $this->hasMany(Relatorioescala::class)->where('ativo', true);
    }
}
